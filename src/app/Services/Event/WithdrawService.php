<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\Transaction;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\AccountNotFoundException;
use App\Services\Exceptions\WithdrawServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\Amount;
use Throwable;

class WithdrawService
{
    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param RecordTransactionService $transactionService
     */
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
        private RecordTransactionService $transactionService
    ) {
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws AccountNotFoundException
     * @throws WithdrawServiceException
     */
    public function withdraw(Transaction $transaction): void
    {

        $account = $this->accountRepository->getAccountById($transaction->origin->toInt());

        if (is_null($account)) {
            throw new AccountNotFoundException(
                message: sprintf('Account of id [%s] not found', $transaction->origin->toInt())
            );
        }

        if ($transaction->amount->toFloat() > $account->amount->toFloat()) {
            throw new WithdrawServiceException(
                message: sprintf(
                    'You don\'t have enough funds [balance: %d]
                    to withdraw [amount: %d]',
                    $account->amount->toFloat(),
                    $transaction->amount->toFloat()
                ),
                code: 400
            );
        }

        $account->amount = Amount::fromFloat($account->amount->toFloat() - $transaction->amount->toFloat());

        try {
            $this->accountRepository->updateBalance($account);
            $this->transactionService->recordTransaction($transaction);
        } catch (Throwable $throwable) {
            throw new WithdrawServiceException(
                message: sprintf('Error executing withdraw to account of id [%s]', $transaction->origin->toInt()),
                code: 500,
                previous: $throwable
            );
        }
    }

}
