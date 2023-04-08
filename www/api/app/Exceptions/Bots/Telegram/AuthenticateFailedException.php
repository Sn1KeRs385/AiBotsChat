<?php

namespace App\Exceptions\Bots\Telegram;

use Illuminate\Http\Response;

class AuthenticateFailedException extends AbstractTelegramException
{
    protected int $errorCode = Response::HTTP_UNAUTHORIZED;
    protected string $errorMessageCode = 'TELEGRAM_AUTHENTICATE_FAILED';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.telegram.authenticate_failed');

        parent::__construct();
    }
}
