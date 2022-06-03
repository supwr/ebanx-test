<?php

namespace App\Services\Account;

use App\Entities\AccountFactory;
use App\Entities\Transaction;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\CreateAccountServiceException;
use App\Services\Transaction\RecordTransactionService;
use Throwable;

class CreateAccountService
{
    /**
     * @param RecordTransactionService $transactionService
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(
        private RecordTransactionService $transactionService,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws CreateAccountServiceException
     */
    public function createAccount(Transaction $transaction): void
    {
        try {
            $account = AccountFactory::fromArray([
                'id' => $transaction->destination->toInt(),
                'amount' => $transaction->amount->toFloat(),
            ]);

            $this->accountRepository->createAccount($account);
            $this->transactionService->recordTransaction($transaction);
        } catch (Throwable $throwable) {
            throw new CreateAccountServiceException(
                message: sprintf('Error creating account of id [%s]', $transaction->destination->toInt()),
                code: 500,
                previous: $throwable
            );
        }
    }
}
