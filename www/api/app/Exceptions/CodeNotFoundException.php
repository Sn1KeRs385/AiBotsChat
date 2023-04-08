<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class CodeNotFoundException extends AbstractApiException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'CODE_NOT_FOUND';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.code_not_found');

        parent::__construct();
    }
}
