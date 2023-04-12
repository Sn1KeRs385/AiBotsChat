<?php

namespace App\Repositories;

use App\Models\GptChat;
use App\Models\User;

class GptChatRepository
{
    public function getActualChatForUser(User $user): GptChat
    {
        $chat = GptChat::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->first();

        if (!$chat) {
            $chat = GptChat::create(['user_id' => $user->id]);
        }

        return $chat;
    }
}
