<?php

declare(strict_types=1);

namespace App\Entities;

use App\Interfaces\Stringable;

class Account implements Stringable
{
    public function __construct(
        public ?int $id,
        public float $amount
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount
        ];
    }
}
