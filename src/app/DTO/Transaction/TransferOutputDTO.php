<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

class TransferOutputDTO implements OutputDTOInterface
{
    public function __construct(
        public int $originAccountId,
        public int $destinationAccountId,
        public float $originAccountBalance,
        public float $destinationAccountBalance,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            originAccountId: $data['origin_account_id'],
            destinationAccountId: $data['destination_account_id'],
            originAccountBalance: $data['origin_account_balance'],
            destinationAccountBalance: $data['destination_account_balance']
        );
    }

    public function toArray(): array
    {
        return [
            'origin' => [
                'id' => $this->originAccountId,
                'balance' => $this->originAccountBalance
            ],
            'destination' => [
                'id' => $this->destinationAccountId,
                'balance' => $this->destinationAccountBalance
            ]
        ];
    }
}
