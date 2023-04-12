<?php

namespace App\Jobs;

use App\Enums\GptRole;
use App\Enums\OpenAiModel;
use App\Models\GptChat;
use App\Models\GptChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI;
use OpenAI\Responses\Chat\CreateResponseMessage;
use OpenAI\Responses\Chat\CreateStreamedResponse;
use SergiX44\Nutgram\Telegram\Attributes\ChatActions;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

class GetAnswerFromGptApiOld implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected GptChat $chat, protected Message $messageForResult)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $messages = $this->chat->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function (GptChatMessage $message) {
                return [
                    'role' => $message->role,
                    'content' => $message->content,
                ];
            })
            ->toArray();

        if (count($messages) === 0) {
            return;
        }

        if (config('integrations.open_ai.stream_enabled')) {
            $this->chat->messages()->create($this->getFromStream($messages));
        } else {
            $this->chat->messages()->create($this->getFromNonStream($messages));
        }
    }

    protected function getFromNonStream(array $messages): array
    {
        $client = OpenAI::client(config('integrations.open_ai.api_key'));

        $response = $client->chat()->create([
            'model' => OpenAiModel::GPT_35_TURBO->value,
            'messages' => $messages,
        ]);

        /** @var CreateResponseMessage $messageResult */
        $messageResult = $response->choices[0]->message;

        $this->trySendTgMessage($messageResult->content);

        return [
            'role' => $messageResult->role,
            'content' => $messageResult->content,
            'extra_data' => $response->toArray(),
        ];
    }

    protected function getFromStream(array $messages): array
    {
        $client = OpenAI::client(config('integrations.open_ai.api_key'));

        $stream = $client->chat()->createStreamed([
            'model' => OpenAiModel::GPT_35_TURBO->value,
            'messages' => $messages,
        ]);

        $result = '';
        $extraData = null;
        $chunkCount = 0;
        $lastUpdateMessage = now();
        foreach ($stream as $streamChunk) {
            $chunkCount += 1;
            /** @var CreateStreamedResponse $streamChunk */
            $extraData = $streamChunk->toArray();
            $content = $streamChunk->choices[0]->delta->content;
            if ($content) {
                $result .= $content;
            }
            if ($result !== '' && $lastUpdateMessage < now()->subSeconds(2)) {
                $lastUpdateMessage = now();
                $this->trySendTgMessage($result, true);
                try {
                    $this->messageForResult->getBot()->sendChatAction(
                        ChatActions::TYPING,
                        [
                            'chat_id' => $this->messageForResult->chat->id,
                        ]
                    );
                } catch (\Throwable $exception) {
                }
            }
        }

        $this->trySendTgMessage($result);

        return [
            'role' => GptRole::ASSISTANT,
            'content' => $result,
            'extra_data' => [...$extraData, 'chunkCount' => $chunkCount],
        ];
    }

    protected function trySendTgMessage(string $result, bool $withContinue = false)
    {
        if ($withContinue) {
            try {
                $resultToSend = $result . "\n...<b>Продолжение следует</b>...";
                $this->sendTgMessage($resultToSend, ParseMode::HTML);
            } catch (\Throwable $exception) {
            }
        }

        try {
            $this->sendTgMessage($result, ParseMode::MARKDOWN);
        } catch (\Throwable $exception) {
            try {
                $this->sendTgMessage($result, ParseMode::MARKDOWN_LEGACY);
            } catch (\Throwable $exception) {
                try {
                    $this->sendTgMessage($result, ParseMode::HTML);
                } catch (\Throwable $exception) {
                }
            }
        }
    }

    protected function sendTgMessage(string $result, string $parseMode)
    {
        $this->messageForResult->getBot()->editMessageText(
            $result,
            [
                'chat_id' => $this->messageForResult->chat->id,
                'message_id' => $this->messageForResult->message_id,
                'parse_mode' => $parseMode,
            ]
        );
    }
}
