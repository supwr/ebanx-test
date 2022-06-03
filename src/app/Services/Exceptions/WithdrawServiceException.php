<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use Exception;
use Throwable;

class WithdrawServiceException extends Exception
{
    public function __construct(
        string $message = 'Error making withdraw',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
