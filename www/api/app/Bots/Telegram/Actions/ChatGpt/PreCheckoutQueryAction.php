<?php

namespace App\Bots\Telegram\Actions\ChatGpt;

use App\Bots\Telegram\Actions\AbstractAction;
use App\Bots\Telegram\Actions\Traits\ActionRouteInfoMapper;
use App\Bots\Telegram\Facades\TelegramWebhook;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

class PreCheckoutQueryAction extends AbstractAction
{
    use ActionRouteInfoMapper;

    public function __invoke(): void
    {
        TelegramWebhook::getBot()->answerPreCheckoutQuery(
            true,
            ['pre_checkout_query_id' => TelegramWebhook::getData()->pre_checkout_query->id]
        );
    }

    public static function getPaths(): array
    {
        return [];
    }

    public static function getAvailableWebhookTypes(): array
    {
        return [UpdateTypes::PRE_CHECKOUT_QUERY];
    }
}
