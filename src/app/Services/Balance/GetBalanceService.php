<?php

declare(strict_types=1);

namespace App\Services\Balance;

use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\BalanceNotFoundException;
use App\ValueObjects\Amount;

class GetBalanceService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(private AccountRepositoryInterface $accountRepository)
    {
    }

    /**
     * @param int $id
     * @return Amount
     * @throws BalanceNotFoundException
     */
    public function getBalance(int $id): Amount
    {
        $account = $this->accountRepository->getAccountById($id);

        if (is_null($account)) {
            throw new BalanceNotFoundException(
                message: sprintf('Balance of id [%s] not found', $id),
                code: 404
            );
        }

        return $account->amount;
    }
}
