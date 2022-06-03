<?php

declare(strict_types=1);

namespace App\Http\Controllers\Exceptions;

use Exception;
use Throwable;

class InvalidTransactionTypeException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Invalid transaction type',
        int $code = 400,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
