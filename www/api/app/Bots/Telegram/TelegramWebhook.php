<?php

namespace App\Bots\Telegram;


use App\Models\TgUserState;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SergiX44\Nutgram\Telegram\Types\Common\Update;

class TelegramWebhook
{
    protected ?User $user;
    protected ?TgUserState $state;

    public function __construct(
        protected readonly TelegramBot $bot,
        protected readonly Update $update,
        protected readonly array $pureWebhookData
    ) {
    }

    public function getBot(): TelegramBot
    {
        return $this->bot;
    }

    public function getData(): Update
    {
        return $this->update;
    }

    public function getPureData(): array
    {
        return $this->pureWebhookData;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        if (!$this->user) {
            throw new ModelNotFoundException();
        }
        return $this->user;
    }

    public function setState(TgUserState $state): void
    {
        $this->state = $state;
    }

    public function getState(): TgUserState
    {
        if (!$this->state) {
            throw new ModelNotFoundException();
        }
        return $this->state;
    }
}
