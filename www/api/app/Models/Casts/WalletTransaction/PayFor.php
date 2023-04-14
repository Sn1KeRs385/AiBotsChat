<?php

namespace App\Models\Casts\WalletTransaction;

use Spatie\LaravelData\Data;

class PayFor extends Data
{
    public ?string $type;
    public ?int $id;
}
