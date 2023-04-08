<?php

namespace App\Bots\Telegram\Actions;

interface ActionContract
{

    public function __invoke(): void;
}
