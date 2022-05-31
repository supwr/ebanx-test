<?php

declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\Arrayable;
use App\ValueObjects\TransactionType;
use DateTimeImmutable;
use App\ValueObjects\Amount;

class Transaction implements Arrayable
{
    public function __construct(
        public ?int $id,
        public TransactionType $type,
        public Amount $amount,
        public Account $origin,
        public Account $destination,
        public ?DateTimeImmutable $createdAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->toString(),
            'amount' => $this->amount->toFloat(),
            'origin' => $this->origin->id,
            'destination' => $this->destination->id,
            'created_at' => $this->createdAt
        ];
    }
}
