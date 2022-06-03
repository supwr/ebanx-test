<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\AccountFactory;
use App\Entities\Transaction;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\DepositServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\Amount;
use Throwable;

class DepositService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param RecordTransactionService $transactionService
     * @param CreateAccountService $createAccountService
     */
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
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
            $account = $this->accountRepository->getAccountById($transaction->destination->toInt());

            if (!$account) {
                $this->createAccountService->createAccount($transaction);
                return;
            }

            $account->amount = Amount::fromFloat(
                $account->amount->toFloat() + $transaction->amount->toFloat()
            );

            $this->accountRepository->updateBalance($account);
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
