<?php

namespace App\Http\Controllers\Api\Telegram;

use App\Bots\Telegram\ActionRouters\BaseActionRouter;
use App\Bots\Telegram\Actions\ActionContract;
use App\Bots\Telegram\Facades\TelegramWebhook;
use Illuminate\Http\Response;

class WebhookController
{
    public function chatGpt(BaseActionRouter $actionRouter): \Illuminate\Http\JsonResponse
    {
        $actions = $actionRouter->getAction(TelegramWebhook::getData());

        $actions->each(function (ActionContract $action) {
            $action->__invoke();
        });

        for ($i = 0; $i < 10; $i++) {
            $nextAction = TelegramWebhook::getState()->data->getNextAction();
            if (!$nextAction) {
                break;
            }
            $nextAction->__invoke();
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
