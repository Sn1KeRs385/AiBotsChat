<?php

namespace App\Bots\Telegram\Actions\Traits;

use App\Bots\Telegram\TelegramWebhook;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

trait ParamsParse
{
    protected function getParamsFromWebhookData(TelegramWebhook $webhook): array
    {
        $params = [];
        switch ($webhook->getData()->getType()) {
            case UpdateTypes::MESSAGE:
                $params = [...$params, ...$this->getParamsFromText($webhook->getData()->getMessage()->text)];
                break;
            case UpdateTypes::CALLBACK_QUERY:
                $params = [...$params, ...$this->getParamsFromText($webhook->getData()->callback_query->data)];
                break;
        }
        return $params;
    }

    protected function getParamsFromText(string $text): array
    {
        $params = [];

        $textSplit = explode('-', $text);
        foreach ($textSplit as $textPart) {
            $paramsSplit = explode('=', $textPart);
            if (count($paramsSplit) === 2) {
                $params[$paramsSplit[0]] = $paramsSplit[1];
            }
        }

        return $params;
    }
}
