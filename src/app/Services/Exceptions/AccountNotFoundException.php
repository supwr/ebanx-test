<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use Exception;
use Throwable;

class AccountNotFoundException extends Exception
{
    public function __construct(
        string $message = 'Account not found',
        int $code = 404,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
