<?php

namespace App\Bots\Telegram\Actions\Traits;

use App\Bots\Telegram\Dto\ActionRouteInfo;

trait ActionRouteInfoMapper
{
    public static function getActionRouteInfo(): ActionRouteInfo
    {
        return ActionRouteInfo::from([
            'paths' => static::getPaths(),
            'action' => app(static::class),
            'isVisible' => static::getIsVisible(),
            'description' => static::getDescription(),
            'availableWebhookTypes' => static::getAvailableWebhookTypes(),
        ]);
    }
}
