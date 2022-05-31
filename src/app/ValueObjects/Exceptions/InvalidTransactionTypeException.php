<?php

declare(strict_types=1);

namespace App\ValueObjects\Exceptions;

use Exception;
use Throwable;

class InvalidTransactionTypeException extends Exception
{
    public function __construct(
        string $message = 'Invalid transaction type',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
