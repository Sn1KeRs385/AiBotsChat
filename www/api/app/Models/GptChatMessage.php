<?php

namespace App\Models;

use App\Models\Casts\NotificationInfo;
use App\Models\Traits\EntityPhpDoc;
use App\Models\Traits\Relations\GptChatMessageRelations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $chat_id
 * @property string $role
 * @property string $content
 * @property bool $is_stream
 * @property bool $is_stream_ended
 * @property int $chunk_count
 * @property array|null $extra_data
 * @property NotificationInfo|null $notification_info
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class GptChatMessage extends Model
{
    use EntityPhpDoc;
    use GptChatMessageRelations;

    protected $fillable = [
        'chat_id',
        'role',
        'content',
        'is_stream',
        'is_stream_ended',
        'chunk_count',
        'extra_data',
        'notification_info',
    ];

    protected $casts = [
        'extra_data' => 'array',
        'notification_info' => NotificationInfo::class,
    ];

    public function getMorphClass(): string
    {
        return 'GptChatMessage';
    }
}
