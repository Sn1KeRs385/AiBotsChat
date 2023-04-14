<?php

namespace App\Models\Casts\WalletTransaction;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Spatie\LaravelData\Data;

class ExtraInfoData extends Data implements Castable
{

    public ?PayFor $payFor;

    public static function castUsing(array $arguments): string
    {
        return ExtraInfoDataCaster::class;
    }
}
