<?php

declare(strict_types=1);

namespace App\Services\Account;

use App\Entities\Account;
use App\Interfaces\Repositories\AccountRepositoryInterface;

class UpdateAccountBalanceService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(private AccountRepositoryInterface $accountRepository)
    {
    }

    /**
     * @param Account $account
     * @return void
     */
    public function updateBalance(Account $account): void
    {
        $this->accountRepository->updateBalance($account);
    }
}
