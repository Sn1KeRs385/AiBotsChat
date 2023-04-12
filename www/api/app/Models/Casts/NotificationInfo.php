<?php

namespace App\Models\Casts;

use App\Enums\MessengerType;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class NotificationInfo extends Data implements Castable
{
    #[WithCast(EnumCast::class)]
    public MessengerType $type;
    public string|int|null $chatId;
    public string|int|null $messageId;

    public static function castUsing(array $arguments): string
    {
        return NotificationInfoCaster::class;
    }
}
