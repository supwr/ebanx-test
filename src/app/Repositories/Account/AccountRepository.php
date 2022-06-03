<?php

declare(strict_types=1);

namespace App\Repositories\Account;

use App\Entities\Account;
use App\Entities\AccountFactory;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Models\Account as AccountModel;
use App\Repositories\Exceptions\AccountRepositoryException;
use Throwable;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * @param AccountModel $model
     */
    public function __construct(private AccountModel $model)
    {
    }

    /**
     * @param int $id
     * @return Account|null
     */
    public function getAccountById(int $id): ?Account
    {
        $account = $this->model->find($id);

        if (empty($account)) {
            return null;
        }

        return AccountFactory::fromArray(
            [
                'id' => $account['id'],
                'amount' => $account['amount']
            ]
        );
    }

    /**
     * @param Account $account
     * @return void
     * @throws AccountRepositoryException
     */
    public function createAccount(Account $account): void
    {
        try {
            $this->model->create([
                'id' => $account->id->toInt(),
                'amount' => $account->amount->toFloat()
            ]);
        } catch (Throwable $throwable) {
            throw new AccountRepositoryException(
                message: sprintf('Error creating account of id [%s]', $account->id->toInt()),
                code: 500,
                previous: $throwable
            );
        }
    }

    /**
     * @param Account $account
     * @return void
     * @throws AccountRepositoryException
     */
    public function updateBalance(Account $account): void
    {
        try {
            $this->model
                ->find($account->id->toInt())
                ->update([
                   'amount' => $account->amount->toFloat()
                ]);
        } catch (Throwable $throwable) {
            throw new AccountRepositoryException(
                message: sprintf('Error updating balance for account of id [%s]', $account->id->toInt()),
                code: 500,
                previous: $throwable
            );
        }
    }

    /**
     * @return void
     * @throws AccountRepositoryException
     */
    public function resetAll(): void
    {
        try {
            $this->model->truncate();
        } catch (Throwable $throwable) {
            throw new AccountRepositoryException(
                message: 'Error reseting accounts',
                code: 500,
                previous: $throwable
            );
        }
    }
}
