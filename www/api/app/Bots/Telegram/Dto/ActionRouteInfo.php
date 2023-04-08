<?php

namespace App\Bots\Telegram\Dto;

use App\Bots\Telegram\Actions\ActionContract;
use Spatie\LaravelData\Data;

class ActionRouteInfo extends Data
{
    /** @var string[] */
    public array $paths;
    public ActionContract $action;
    public bool $isVisible;
    public ?string $description;
    /** @var string[] */
    public array $availableWebhookTypes;
}
