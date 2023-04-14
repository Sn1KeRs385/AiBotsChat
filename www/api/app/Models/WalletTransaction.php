<?php

namespace App\Models;

use App\Enums\WalletTransactionType;
use App\Models\Casts\WalletTransaction\ExtraInfoData;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\WalletTransactionRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read int $wallet_id
 * @property WalletTransactionType $type
 * @property int $amount
 * @property array $wallet_before
 * @property array $wallet_after
 * @property ExtraInfoData $extra_info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class WalletTransaction extends Model
{
    use EntityPhpDoc;
    use SoftDeletes;
    use WalletTransactionRelations;

    protected $fillable = [
        'type',
        'amount',
        'wallet_before',
        'wallet_after',
        'extra_info',
    ];

    protected $casts = [
        'wallet_before' => 'array',
        'wallet_after' => 'array',
        'type' => WalletTransactionType::class,
        'extra_info' => ExtraInfoData::class,
    ];

    public function getMorphClass(): string
    {
        return 'WalletTransaction';
    }
}
