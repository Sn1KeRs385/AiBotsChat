<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class AuthorizationWithCredentialsFailedException extends AbstractApiException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'AUTHORIZATION_WITH_CREDENTIALS_FAILED';

    public function __construct()
    {
        $this->errorMessage = __('auth.failed');

        parent::__construct();
    }
}
