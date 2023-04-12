<?php

namespace App\Models\Traits\Relations;


use App\Models\GptChatMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property User $user
 */
trait GptChatRelations
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GptChatMessage::class, 'chat_id');
    }
}
