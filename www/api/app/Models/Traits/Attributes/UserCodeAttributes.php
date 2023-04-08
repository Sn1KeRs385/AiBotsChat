<?php

namespace App\Models\Traits\Attributes;

/**
 * @property int $characterNumber
 * @property bool $isNumberCode
 */
trait UserCodeAttributes
{
    public function getCharacterNumberAttribute(): int
    {
        return mb_strlen($this->code);
    }

    public function getIsNumberCodeAttribute(): bool
    {
        return is_numeric($this->code);
    }
}
