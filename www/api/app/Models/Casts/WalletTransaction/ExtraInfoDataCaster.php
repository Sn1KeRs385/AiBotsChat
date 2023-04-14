<?php

namespace App\Models\Casts\WalletTransaction;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ExtraInfoDataCaster implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return ExtraInfoData::from(json_decode($value, true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (!$value instanceof ExtraInfoData) {
            throw new InvalidArgumentException('The given value is not an ExtraInfoData instance.');
        }

        return json_encode($value->toArray());
    }
}
