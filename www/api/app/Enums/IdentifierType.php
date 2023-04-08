<?php

namespace App\Enums;


enum IdentifierType: string
{
    case PHONE = 'PHONE';
    case EMAIL = 'EMAIL';
    case LOGIN = 'LOGIN';
    case TG_USER_ID = 'TG_USER_ID';
}
