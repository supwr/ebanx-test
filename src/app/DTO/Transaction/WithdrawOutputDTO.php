<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

use App\Interfaces\DTO\OutputDTOInterface;

class WithdrawOutputDTO extends DepositOutputDTO implements OutputDTOInterface
{
    public function __construct(
        public int $accountId,
        public float $balance,
    ) {
        parent::__construct($this->accountId, $this->balance);
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return [
            'origin' => [
                'id' => (string) $this->accountId,
                'balance' => $this->balance
            ]
        ];
    }
}
