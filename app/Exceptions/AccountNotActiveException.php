<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AccountNotActiveException extends Exception
{
    protected $message = 'Account is not active';
    protected $code = 403;
}
