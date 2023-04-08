<?php

namespace App\Models;

use App\Models\Traits\Attributes\UserCodeAttributes;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\UserCodeRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $type
 * @property string $code
 * @property Carbon|null $used_at
 * @property Carbon|null $expired_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class UserCode extends Model
{
    use EntityPhpDoc;
    use UserCodeAttributes;
    use UserCodeRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'code',
        'used_at',
        'expired_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function getMorphClass(): string
    {
        return 'UserCode';
    }
}
