<?php

namespace App\Models\Traits\Relations;


use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property User $user
 */
trait UserIdentifierRelations
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
