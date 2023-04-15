<?php

namespace App\Models;

use App\Enums\WalletType;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Methods\WalletMethods;
use App\Models\Traits\Relations\WalletRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property WalletType $wallet_type
 * @property int $price
 * @property int $deposit
 * @property bool $is_active
 * @property int $weight
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Tariff extends Model
{
    use EntityPhpDoc;

    protected $fillable = [
        'wallet_type',
        'price',
        'deposit',
        'is_active',
        'weight',
    ];

    protected $casts = [
        'wallet_type' => WalletType::class,
    ];

    public function getMorphClass(): string
    {
        return 'Tariff';
    }
}
