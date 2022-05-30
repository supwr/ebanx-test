<?php

declare(strict_types=1);

namespace App\Entities;

use App\ValueObjects\TransactionType;
use DateTimeImmutable;
use App\ValueObjects\Amount;

class Transaction
{
    public function __construct(
        public ?int $id,
        public TransactionType $type,
        public Amount $amount,
        public Account $origin,
        public Account $destination,
        public DateTimeImmutable $date
    ) {
    }
}
