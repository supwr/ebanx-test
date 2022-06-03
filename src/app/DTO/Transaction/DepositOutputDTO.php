<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

use App\Interfaces\DTO\OutputDTOInterface;
use Spatie\DataTransferObject\DataTransferObject;

class DepositOutputDTO extends DataTransferObject implements OutputDTOInterface
{
    public function __construct(
        public int $accountId,
        public float $balance,
    ) {
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data): static
    {
        return new static(
            accountId: $data['account_id'],
            balance: $data['balance']
        );
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return [
            'destination' => [
                'id' => (string) $this->accountId,
                'balance' => $this->balance
            ]
        ];
    }
}
