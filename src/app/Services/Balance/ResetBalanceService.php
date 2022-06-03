<?php

declare(strict_types=1);

namespace App\Services\Balance;

use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;

class ResetBalanceService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private TransactionRepositoryInterface $transactionRepository
    ) {
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->accountRepository->resetAll();
        $this->transactionRepository->resetAll();
    }
}
