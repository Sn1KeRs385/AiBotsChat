<?php

namespace App\Bots\Telegram\Types\Common;


use App\Bots\Telegram\Attributes\UpdateTypes;

class Update extends \SergiX44\Nutgram\Telegram\Types\Common\Update
{
    public function getType(): ?string
    {
        if ($this->message !== null && $this->message->successful_payment) {
            return UpdateTypes::SUCCESSFUL_PAYMENT;
        }

        return parent::getType();
    }
}
