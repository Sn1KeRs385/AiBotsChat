<?php

namespace App\Bots\Telegram\ActionRouters;

use App\Bots\Telegram\Actions\ActionContract;
use App\Bots\Telegram\Attributes\UpdateTypes;
use App\Bots\Telegram\Dto\ActionRouteInfo;
use App\Bots\Telegram\Types\Common\Update;
use Illuminate\Support\Collection;

abstract class BaseActionRouter
{
    /** @var Collection<int, ActionRouteInfo> */
    protected readonly Collection $routes;

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
            UpdateTypes::PRE_CHECKOUT_QUERY => $filteredRoutes,
            UpdateTypes::SUCCESSFUL_PAYMENT => $filteredRoutes,
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
        $routesFiltered = $routes->filter(function (ActionRouteInfo $actionRouteInfo) use ($text) {
            $pregTest = false;
            foreach ($actionRouteInfo->paths as $path) {
                $pregTest = preg_match($path, $text) === 1;
                if ($pregTest) {
                    break;
                }
            }

            return $pregTest;
        });

        if ($routesFiltered->count() === 0) {
            $routesFiltered = $routes->filter(function (ActionRouteInfo $actionRouteInfo) {
                return count($actionRouteInfo->paths) === 0;
            });
        }

        return $routesFiltered;
    }
}
