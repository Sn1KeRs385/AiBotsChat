<?php

namespace App\Models;

use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\UserIdentifierRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $type
 * @property string $value
 * @property array|null $extra_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class UserIdentifier extends Model
{
    use EntityPhpDoc;
    use SoftDeletes;
    use UserIdentifierRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'value',
        'extra_data',
    ];

    protected $casts = [
        'extra_data' => 'array',
    ];

    public function getMorphClass(): string
    {
        return 'UserIdentifier';
    }
}
