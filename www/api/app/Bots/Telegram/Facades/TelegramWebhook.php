<?php

namespace App\Bots\Telegram\Facades;


use App\Bots\Telegram\TelegramBot;
use App\Models\TgUserState;
use App\Models\User;
use Illuminate\Support\Facades\Facade;
use SergiX44\Nutgram\Telegram\Types\Common\Update;

/**
 * @method static TelegramBot getBot()
 * @method static Update getData()
 * @method static array getPureData()
 * @method static void setUser(User $user)
 * @method static User getUser()
 * @method static void setState(TgUserState $state)
 * @method static TgUserState getState()
 * @method static \App\Bots\Telegram\TelegramWebhook getFacadeRoot()
 */
class TelegramWebhook extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'TelegramWebhookData';
    }
}
