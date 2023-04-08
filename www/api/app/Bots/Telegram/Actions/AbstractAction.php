<?php

namespace App\Bots\Telegram\Actions;

use App\Bots\Telegram\Dto\ActionRouteInfo;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;

abstract class AbstractAction implements ActionContract
{
    /** @return string[] */
    abstract public static function getPaths(): array;

    public static function getIsVisible(): bool
    {
        return false;
    }

    public static function getDescription(): ?string
    {
        return '';
    }

    abstract public static function getActionRouteInfo(): ActionRouteInfo;

    /** @return string[] */
    abstract public static function getAvailableWebhookTypes(): array;
}
