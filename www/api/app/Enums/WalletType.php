<?php

namespace App\Enums;


enum WalletType: string
{
    case GPT = 'GPT';

    public function getDefaultBalance(): int
    {
        return match ($this) {
            self::GPT => 100000,
            default => 0,
        };
    }

    public function getDefaultDebt(): int
    {
        return match ($this) {
            default => 0,
        };
    }
}
