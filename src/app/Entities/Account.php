<?php

declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\Arrayable;
use App\ValueObjects\AccountId;
use App\ValueObjects\Amount;

class Account implements Arrayable
{
    public function __construct(
        public ?AccountId $id,
        public Amount $amount
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toInt(),
            'amount' => $this->amount->toFloat()
        ];
    }
}
