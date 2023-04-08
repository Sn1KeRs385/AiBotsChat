<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class CustomMessageAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __invoke(): void
    {
        TelegramWebhook::getBot()->sendMessage(
            TelegramWebhook::getData()->getMessage()->text,
            [
                'chat_id' => TelegramWebhook::getData()->getChat()->id,
                'parse_mode' => ParseMode::HTML,
            ]
        );
    }

    public static function getPaths(): array
    {
        return [];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::MESSAGE];
    }
}
