<?php

namespace App\Models\Casts\TgUserState;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class UserStateDataCaster implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return UserStateData::from(json_decode($value, true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (!$value instanceof UserStateData) {
            throw new InvalidArgumentException('The given value is not an UserStateData instance.');
        }

        return json_encode($value->toArray());
    }
}
