<?php

namespace App\Exceptions\Models\Wallet;

use App\Exceptions\Models\AbstractModelException;
use Illuminate\Http\Response;

class TransactionTypeCreateMethodNotImplementException extends AbstractModelException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'TRANSACTION_TYPE_CREATE_METHOD_NOT_IMPLEMENT';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.wallet.transaction_type_create_method_not_implement');

        parent::__construct();
    }
}
