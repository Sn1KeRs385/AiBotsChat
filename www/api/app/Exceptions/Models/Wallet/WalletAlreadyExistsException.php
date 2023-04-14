<?php

namespace App\Exceptions\Models\Wallet;

use App\Exceptions\Models\AbstractModelException;
use Illuminate\Http\Response;

class WalletAlreadyExistsException extends AbstractModelException
{
    protected int $errorCode = Response::HTTP_BAD_REQUEST;
    protected string $errorMessageCode = 'WALLET_ALREADY_EXISTS';

    public function __construct()
    {
        $this->errorMessage = __('exceptions.wallet.wallet_already_exists');

        parent::__construct();
    }
}
