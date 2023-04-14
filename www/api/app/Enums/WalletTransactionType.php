<?php

namespace App\Enums;


enum WalletTransactionType: string
{
    //Пополнение
    case REFILL = 'REFILL';
    //Списание
    case WITHDRAW = 'WITHDRAW';
    //Списание
    case WITHDRAW_DEBT_POSSIBLE = 'WITHDRAW_DEBT_POSSIBLE';
}
