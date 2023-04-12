<?php

namespace App\Services;

use App\Enums\GptRole;
use App\Exceptions\Models\GptChat\MessageCannotBeEmpty;
use App\Jobs\GetAnswerFromGptApi;
use App\Models\Casts\NotificationInfo;
use App\Models\GptChat;
use App\Models\User;
use App\Repositories\GptChatRepository;

class GptChatService
{
    public function __construct(protected GptChatRepository $gptChatRepository)
    {
    }

    public function addMessageToChatByUser(
        string $text,
        User $user,
        GptRole $role = GptRole::USER,
        NotificationInfo $notificationInfo = null,
        bool $sendAsStream = null,
    ): GptChat {
        if ($text === '') {
            throw new MessageCannotBeEmpty();
        }

        if ($sendAsStream === null) {
            $sendAsStream = config('integrations.open_ai.stream_enabled');
        }

        $chat = $this->gptChatRepository->getActualChatForUser($user);

        $chat->messages()->create([
            'role' => $role,
            'content' => $text,
        ]);

        GetAnswerFromGptApi::dispatch($chat, $notificationInfo, $sendAsStream)->onQueue('chat_gpt');

        return $chat;
    }
}
