<?php

namespace App\Services\Event;

use App\Entities\AccountFactory;
use App\Entities\Transaction;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\CreateAccountServiceException;
use App\Services\Transaction\RecordTransactionService;
use Throwable;

class CreateAccountService
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private RecordTransactionService $transactionService
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
