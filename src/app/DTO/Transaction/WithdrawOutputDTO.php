<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

class WithdrawOutputDTO extends DepositOutputDTO implements OutputDTOInterface
{
    public function __construct(
        public int $accountId,
        public float $balance,
    ) {
        parent::__construct($this->accountId, $this->balance);
    }
}
