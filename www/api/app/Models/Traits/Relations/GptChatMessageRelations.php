<?php

namespace App\Models\Traits\Relations;


use App\Models\GptChat;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property GptChat $chat
 */
trait GptChatMessageRelations
{
    public function chat(): BelongsTo
    {
        return $this->belongsTo(GptChat::class, 'chat_id');
    }
}
