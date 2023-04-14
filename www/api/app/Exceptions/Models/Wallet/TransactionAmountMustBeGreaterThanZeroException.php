<?php

namespace App\Exceptions\Models\Wallet;

use App\Exceptions\Models\AbstractModelException;
use Illuminate\Http\Response;

class TransactionAmountMustBeGreaterThanZeroException extends AbstractModelException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'TRANSACTION_AMOUNT_MUST_BE_GREATER_THAN_ZERO';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.wallet.transaction_amount_must_be_greater_than_zero');

        parent::__construct();
    }
}
