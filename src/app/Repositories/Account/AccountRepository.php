<?php

declare(strict_types=1);

namespace App\Repositories\Account;

use App\Entities\Account;
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


    public function getAccountById(int $id): Account
    {
        return $this->model->find();
    }

    /**
     * @param Account $account
     * @return void
     * @throws AccountRepositoryException
     */
    public function createAccount(Account $account): void
    {
        try{
            AccountModel::create([
                'id' => $account->id,
                'amount' => $account->amount->toFloat()
            ]);
        } catch (Throwable $throwable) {
            throw new AccountRepositoryException(
                message: sprintf('Error creating account of id [%s]', $account->id),
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
                ->find($account->id)
                ->update([
                   'balance' => $account->amount->toFloat()
                ]);
        } catch (Throwable $throwable) {
            throw new AccountRepositoryException(
                message: sprintf('Error updating balance for account of id [%s]', $account->id),
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
            AccountModel::truncate();
        } catch(Throwable $throwable) {
            throw new AccountRepositoryException(
                message: 'Error reseting accounts',
                code: 500,
                previous: $throwable
            );
        }
    }
}
