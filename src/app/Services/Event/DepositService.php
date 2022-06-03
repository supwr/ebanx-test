<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\Transaction;
use App\Services\Account\CreateAccountService;
use App\Services\Account\GetAccountService;
use App\Services\Account\UpdateAccountBalanceService;
use App\Services\Exceptions\DepositServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\Amount;
use Throwable;

class DepositService
{
    /**
     * @param GetAccountService $getAccountService
     * @param UpdateAccountBalanceService $updateBalanceService
     * @param RecordTransactionService $transactionService
     * @param CreateAccountService $createAccountService
     */
    public function __construct(
        private GetAccountService $getAccountService,
        private UpdateAccountBalanceService $updateBalanceService,
        private RecordTransactionService $transactionService,
        private CreateAccountService $createAccountService
    ) {
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws DepositServiceException
     */
    public function deposit(Transaction $transaction): void
    {
        try {
            $account = $this->getAccountService->getAccountById($transaction->destination->toInt());

            if (!$account) {
                $this->createAccountService->createAccount($transaction);
                return;
            }

            $account->amount = Amount::fromFloat(
                $account->amount->toFloat() + $transaction->amount->toFloat()
            );

            $this->updateBalanceService->updateBalance($account);
            $this->transactionService->recordTransaction($transaction);
        } catch (Throwable $throwable) {
            throw new DepositServiceException(
                message: sprintf('Error executing deposit to account of id [%s]', $transaction->destination->toInt()),
                code: 500,
                previous: $throwable
            );
        }
    }
}
