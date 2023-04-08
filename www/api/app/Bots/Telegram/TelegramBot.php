<?php

namespace App\Bots\Telegram;


class TelegramBot extends \SergiX44\Nutgram\Nutgram
{
    public static function getPublicId(): string
    {
        return config('telegram.public_id');
    }
}
