<?php

namespace App\Exceptions\Models\GptChat;

use App\Exceptions\Models\AbstractModelException;
use Illuminate\Http\Response;

class MessageCannotBeEmpty extends AbstractModelException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'MESSAGE_CANNOT_BE_EMPTY';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.gpt_chat.message_cannot_be_empty');

        parent::__construct();
    }
}
