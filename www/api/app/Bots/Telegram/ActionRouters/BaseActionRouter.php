<?php

namespace App\Bots\Telegram\ActionRouters;

use App\Bots\Telegram\Actions\ActionContract;
use App\Bots\Telegram\Dto\ActionRouteInfo;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Telegram\Attributes\UpdateTypes;
use SergiX44\Nutgram\Telegram\Types\Common\Update;

abstract class BaseActionRouter
{
    /** @var Collection<int, ActionRouteInfo> */
    protected readonly Collection $routes;

    protected function getCustomMessageAction(): ?ActionRouteInfo
    {
        return null;
    }

    /**
     * @param  Update  $webhookData
     * @return Collection<int, ActionContract>
     */
    public function getAction(Update $webhookData): Collection
    {
        $filteredRoutes = $this->routes->filter(function (ActionRouteInfo $actionRouteInfo) use ($webhookData) {
            return in_array($webhookData->getType(), $actionRouteInfo->availableWebhookTypes);
        });

        return (match ($webhookData->getType()) {
            UpdateTypes::MESSAGE => $this->getRoutesListFromText($filteredRoutes, $webhookData->getMessage()->text),
            UpdateTypes::CALLBACK_QUERY => $this->getRoutesListFromText(
                $filteredRoutes,
                $webhookData->callback_query->data
            ),
            UpdateTypes::CHAT_MEMBER => $filteredRoutes,
            default => collect([]),
        })
            ->values()
            ->map(function (ActionRouteInfo $actionRouteInfo) {
                return $actionRouteInfo->action;
            });
    }

    /**
     * @param  string  $text
     * @return Collection<int, ActionContract>
     */
    public function getActionByText(string $text): Collection
    {
        return $this->getRoutesListFromText($this->routes, $text)
            ->values()
            ->map(function (ActionRouteInfo $actionRouteInfo) {
                return $actionRouteInfo->action;
            });
    }

    /**
     * @param  Collection<int, ActionRouteInfo>  $routes
     * @param  string  $text
     * @return Collection<int, ActionRouteInfo>
     */
    protected function getRoutesListFromText(Collection $routes, string $text): Collection
    {
        $routes = $routes->filter(function (ActionRouteInfo $actionRouteInfo) use ($text) {
            $pregTest = false;
            foreach ($actionRouteInfo->paths as $path) {
                $pregTest = preg_match($path, $text) === 1;
                if ($pregTest) {
                    break;
                }
            }

            return $pregTest;
        });

        if ($routes->count() === 0) {
            $customMessageAction = $this->getCustomMessageAction();
            if ($customMessageAction) {
                $routes->push($customMessageAction);
            }
        }

        return $routes;
    }
}
