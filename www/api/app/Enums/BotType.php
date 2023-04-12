<?php

namespace App\Enums;


enum BotType: string
{
    case GPT = 'GPT';

    public function getConfigEquivalent(): string
    {
        return match ($this) {
            self::GPT => 'chat_gpt',
            default => '',
        };
    }
}
