<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use Exception;
use Throwable;

class DepositServiceException extends Exception
{
    public function __construct(
        string $message = 'Error making deposit',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
