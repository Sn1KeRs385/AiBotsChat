<?php

namespace App\Models;

use App\Enums\WalletType;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Methods\WalletMethods;
use App\Models\Traits\Relations\WalletRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read string $owner_type
 * @property-read int $owner_id
 * @property WalletType $type
 * @property int $balance
 * @property int $debt
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Wallet extends Model
{
    use EntityPhpDoc;
    use SoftDeletes;
    use WalletRelations;
    use WalletMethods;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'type',
        'balance',
        'debt',
    ];

    protected $casts = [
        'type' => WalletType::class,
    ];

    public function getMorphClass(): string
    {
        return 'Wallet';
    }
}
