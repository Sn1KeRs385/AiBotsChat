<?php

namespace App\Models\Traits\Relations;


use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Wallet $wallet
 */
trait WalletTransactionRelations
{
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
