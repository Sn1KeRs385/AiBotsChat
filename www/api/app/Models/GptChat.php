<?php

namespace App\Models;

use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\GptChatRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class GptChat extends Model
{
    use EntityPhpDoc;
    use SoftDeletes;
    use GptChatRelations;

    protected $fillable = [
        'user_id',
        'title',
    ];

    public function getMorphClass(): string
    {
        return 'GptChat';
    }
}
