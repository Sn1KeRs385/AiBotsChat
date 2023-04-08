<?php

namespace App\Bots\Telegram\Actions\Traits;

use App\Bots\Telegram\TelegramWebhook;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

trait CallbackQueryMethods
{
    protected function deleteCallbackQueryMessage(TelegramWebhook $webhook): void
    {
        if ($webhook->getData()->getType() !== UpdateTypes::CALLBACK_QUERY) {
            return;
        }
        try {
            $webhook->getData()->getBot()->deleteMessage(
                $webhook->getData()->getMessage()->chat->id,
                $webhook->getData()->getMessage()->message_id
            );
        } catch (\Throwable) {
        }
    }
}
