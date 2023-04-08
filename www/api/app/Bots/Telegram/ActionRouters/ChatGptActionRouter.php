<?php

namespace App\Bots\Telegram\ActionRouters;

use App\Bots\Telegram\Actions\ActionContract;
use App\Bots\Telegram\Actions\ChatGpt\CustomMessageAction;
use App\Bots\Telegram\Actions\ChatGpt\StartAction;
use App\Bots\Telegram\Dto\ActionRouteInfo;
use Illuminate\Support\Collection;

class ChatGptActionRouter extends BaseActionRouter
{
    /** @var Collection<int, ActionRouteInfo> */
    protected readonly Collection $routes;

    public function __construct()
    {
        $this->routes = collect([
            StartAction::getActionRouteInfo(),
        ]);
    }

    protected function getCustomMessageAction(): ActionRouteInfo
    {
        return CustomMessageAction::getActionRouteInfo();
    }
}
