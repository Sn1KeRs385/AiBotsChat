<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class NotificationInfoCaster implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value ? NotificationInfo::from(json_decode($value, true)) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if ($value !== null & !$value instanceof NotificationInfo) {
            throw new InvalidArgumentException('The given value is not an NotificationInfo instance.');
        }

        return $value ? json_encode($value->toArray()) : null;
    }
}
