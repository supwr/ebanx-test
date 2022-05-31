<?php

declare(strict_types=1);

namespace App\Repositories\Exceptions;

use Exception;
use Throwable;

class AccountRepositoryException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Account Repository Exception',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
