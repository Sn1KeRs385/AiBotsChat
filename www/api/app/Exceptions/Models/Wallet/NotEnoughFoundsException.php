<?php

namespace App\Exceptions\Models\Wallet;

use App\Exceptions\Models\AbstractModelException;
use Illuminate\Http\Response;

class NotEnoughFoundsException extends AbstractModelException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'NOT_ENOUGH_FOUNDS';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.wallet.not_enough_founds');

        parent::__construct();
    }
}
