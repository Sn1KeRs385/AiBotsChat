<?php

namespace App\Models;

use App\Models\Casts\TgUserState\UserStateData;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\TgUserStateRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $user_id
 * @property UserStateData $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TgUserState extends Model
{
    use EntityPhpDoc;
    use TgUserStateRelations;

    protected $fillable = [
        'user_id',
        'data',
    ];


    protected $casts = [
        'data' => UserStateData::class,
    ];

    public function getMorphClass(): string
    {
        return 'TgUserBotState';
    }
}
