<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\Account;
use App\Entities\Transaction;
use App\Services\Account\CreateAccountService;
use App\Services\Account\GetAccountService;
use App\Services\Account\UpdateAccountBalanceService;
use App\Services\Exceptions\AccountNotFoundException;
use App\Services\Exceptions\TransferServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\Amount;
use Throwable;

class TransferService
{
    /**
     * @param GetAccountService $accountService
     * @param RecordTransactionService $transactionService
     * @param CreateAccountService $createAccountService
     */
    public function __construct(
        private GetAccountService $accountService,
        private UpdateAccountBalanceService $accountBalanceService,
        private RecordTransactionService $transactionService,
        private CreateAccountService $createAccountService
    ) {
    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws AccountNotFoundException
     * @throws TransferServiceException
     */
    public function transfer(Transaction $transaction): void
    {
        $originAccount = !empty($transaction->origin)
            ? $this->accountService->getAccountById($transaction->origin->toInt())
            : null;

        $destinationAccount = !empty($transaction->destination)
            ? $this->accountService->getAccountById($transaction->destination->toInt())
            : null;

        if (is_null($originAccount)) {
            $this->throwAccountNotFoundException(
                sprintf('Origin account of id [%s] not found', $transaction->origin?->toInt())
            );
        }

        if ($transaction->amount->toFloat() > $originAccount->amount->toFloat()) {
            throw new TransferServiceException(
                message: sprintf(
                    'You don\'t have enough funds [balance: %d]
                    to withdraw [amount: %d]',
                    $originAccount->amount->toFloat(),
                    $transaction->amount->toFloat()
                ),
                code: 400
            );
        }

        try {
            $this->updateOriginAccount($originAccount, $transaction);
            $this->updateDestinationAccount($destinationAccount, $transaction);

            $this->transactionService->recordTransaction($transaction);
        } catch (Throwable $throwable) {
            throw new TransferServiceException(
                message: sprintf(
                    'Error executing transfer from account of id [%s] to account of id [%s]',
                    $transaction->origin->toInt(),
                    $transaction->destination->toInt()
                ),
                code: 500,
                previous: $throwable
            );
        }
    }

    /**
     * @param Account $originAccount
     * @param Transaction $transaction
     * @return void
     */
    private function updateOriginAccount(Account $originAccount, Transaction $transaction): void
    {
        $originAccount->amount = Amount::fromFloat(
            $originAccount->amount->toFloat() - $transaction->amount->toFloat()
        );

        $this->accountBalanceService->updateBalance($originAccount);
    }

    /**
     * @param Account|null $destinationAccount
     * @param Transaction $transaction
     * @return void
     * @throws \App\Services\Exceptions\CreateAccountServiceException
     */
    private function updateDestinationAccount(?Account $destinationAccount, Transaction $transaction): void
    {
        if (is_null($destinationAccount)) {
            $this->createAccountService->createAccount($transaction);
            return;
        }

        $destinationAccount->amount = Amount::fromFloat(
            $destinationAccount->amount->toFloat() + $transaction->amount->toFloat()
        );

        $this->accountBalanceService->updateBalance($destinationAccount);
    }

    /**
     * @param string $message
     * @return void
     * @throws AccountNotFoundException
     */
    private function throwAccountNotFoundException(string $message): void
    {
        throw new AccountNotFoundException(message: $message);
    }
}
