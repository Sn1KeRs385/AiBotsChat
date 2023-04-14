<?php

namespace App\Models\Traits\Relations;


use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property Model $owner
 * @property Collection<int, WalletTransaction>|WalletTransaction[] $transactions
 */
trait WalletRelations
{
    public function owner(): MorphTo
    {
        return $this->morphTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
