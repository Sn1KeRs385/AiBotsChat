<?php

namespace App\Models\Casts\TgUserState;

use App\Bots\Telegram\Actions\ActionContract;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class UserStateData extends Data implements Castable
{

    /** @var ActionContract[] */
    protected ?array $actionsQueue = [];

    public static function castUsing(array $arguments): string
    {
        return UserStateDataCaster::class;
    }

    public function getNextAction(): ?ActionContract
    {
        return array_shift($this->actionsQueue);
    }

    public function addActionsToQueue(ActionContract ...$actions): self
    {
        foreach ($actions as $action) {
            $this->addActionToQueue($action);
        }
        return $this;
    }

    public function addActionToQueue(ActionContract $action): self
    {
        if (!$this->actionsQueue) {
            $this->actionsQueue = [$action];
        } else {
            $this->actionsQueue[] = $action;
        }
        return $this;
    }
}
