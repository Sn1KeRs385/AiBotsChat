<?php

namespace App\Bots\Telegram\Actions\Traits;

use app\Bots\Telegram\ActionRouters\TelegramActionRouter;
use App\Bots\Telegram\TelegramWebhook;

trait BackParamHandler
{
    protected function handleBackParam(string $back, TelegramWebhook $telegramWebhook): void
    {
        /** @var TelegramActionRouter $router */
        $router = app(TelegramActionRouter::class);
        $actions = $router->getActionByText($back);
        $telegramWebhook->getState()->data->addActionsToQueue(...$actions);
    }
}
