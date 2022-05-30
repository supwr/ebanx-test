<?php

declare(strict_types=1);

namespace App\ValueObjects\Exceptions;

use Exception;

class InvalidAmountException extends Exception
{
    public function __construct(
        string $message = 'Invalid amount',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
