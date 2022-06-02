<?php

declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\Arrayable;
use App\ValueObjects\AccountId;
use App\ValueObjects\TransactionType;
use DateTimeImmutable;
use App\ValueObjects\Amount;

class Transaction implements Arrayable
{
    public function __construct(
        public ?int $id,
        public TransactionType $type,
        public Amount $amount,
        public AccountId $origin,
        public AccountId $destination,
        public ?DateTimeImmutable $createdAt
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->toString(),
            'amount' => $this->amount->toFloat(),
            'origin' => $this->origin->toInt(),
            'destination' => $this->destination->toInt(),
            'created_at' => $this->createdAt
        ];
    }
}
