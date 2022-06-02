<?php

declare(strict_types=1);

namespace App\ValueObjects\Exceptions;

use Exception;
use Throwable;

class InvalidAccountIdException extends Exception
{
    public function __construct(
        string $message = 'Invalid account id',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
