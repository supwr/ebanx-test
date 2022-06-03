<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\Transaction;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Exceptions\AccountNotFoundException;
use App\Services\Exceptions\TransferServiceException;
use App\Services\Exceptions\WithdrawServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\Amount;
use Throwable;

class TransferService
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
     * @throws TransferServiceException
     */
    public function transfer(Transaction $transaction): void
    {
        $originAccount = !empty($transaction->origin)
            ? $this->accountRepository->getAccountById($transaction->origin->toInt())
            : null;

        $destinationAccount = !empty($transaction->destination)
            ? $this->accountRepository->getAccountById($transaction->destination->toInt())
            : null;

        if (is_null($originAccount)) {
            $this->throwAccountNotFoundException(
                sprintf('Origin account of id [%s] not found', $transaction->origin?->toInt())
            );
        }

        if (is_null($destinationAccount)) {
            $this->throwAccountNotFoundException(
                sprintf('Destination account of id [%s] not found', $transaction->destination?->toInt())
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

        $originAccount->amount = Amount::fromFloat(
            $originAccount->amount->toFloat() - $transaction->amount->toFloat()
        );

        $destinationAccount->amount = Amount::fromFloat(
            $destinationAccount->amount->toFloat() + $transaction->amount->toFloat()
        );

        try {
            $this->accountRepository->updateBalance($originAccount);
            $this->accountRepository->updateBalance($destinationAccount);

            $this->transactionService->recordTransaction($transaction);
        } catch (Throwable $throwable) {
            throw new TransferServiceException(
                message: sprintf('Error executing transfer from account of id [%s] to account of id [%s]', $transaction->origin->toInt(), $transaction->destination->toInt()),
                code: 500,
                previous: $throwable
            );
        }
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
