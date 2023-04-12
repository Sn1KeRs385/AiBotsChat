<?php

namespace App\Jobs;

use App\Enums\GptRole;
use App\Enums\OpenAiModel;
use App\Models\Casts\NotificationInfo;
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

class GetAnswerFromGptApi implements ShouldQueue
{

    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    protected OpenAI\Client $client;

    public function __construct(
        protected GptChat $chat,
        protected ?NotificationInfo $notificationInfo = null,
        protected bool $sendAsStream = false
    ) {
    }

    public function handle(): void
    {
        $this->client = OpenAI::client(config('integrations.open_ai.api_key'));

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

        if ($this->sendAsStream) {
            $this->writeAnswerFromStream($messages);
        } else {
            $this->writeAnswerFromNonStream($messages);
        }
    }

    protected function writeAnswerFromNonStream(array $messages): void
    {
        $response = $this->client->chat()->create([
            'model' => OpenAiModel::GPT_35_TURBO->value,
            'messages' => $messages,
        ]);

        /** @var CreateResponseMessage $messageResult */
        $messageResult = $response->choices[0]->message;

        $this->chat->messages()->create([
            'role' => $messageResult->role,
            'content' => $messageResult->content,
            'extra_data' => $response->toArray(),
            'notification_info' => $this->notificationInfo,
        ]);
    }

    protected function writeAnswerFromStream(array $messages): void
    {
        /** @var GptChatMessage $gptChatMessage */
        $gptChatMessage = $this->chat->messages()->create([
            'role' => GptRole::ASSISTANT,
            'content' => '',
            'is_stream' => true,
            'is_stream_ended' => false,
            'notification_info' => $this->notificationInfo,
        ]);

        $stream = $this->client->chat()->createStreamed([
            'model' => OpenAiModel::GPT_35_TURBO->value,
            'messages' => $messages,
        ]);

        $result = '';
        $extraData = [];
        $chunkCount = 0;

        foreach ($stream as $streamChunk) {
            $chunkCount += 1;
            /** @var CreateStreamedResponse $streamChunk */
            $extraData[] = $streamChunk->toArray();
            $content = $streamChunk->choices[0]->delta->content;
            if ($content) {
                $result .= $content;
                $gptChatMessage->content = $result;
                $gptChatMessage->save();
            }
        }

        $gptChatMessage->is_stream_ended = true;
        $gptChatMessage->chunk_count = $chunkCount;
        $gptChatMessage->extra_data = $extraData;
        $gptChatMessage->content = $result;
        $gptChatMessage->save();
    }
}
