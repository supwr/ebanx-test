<?php

declare(strict_types=1);

namespace App\Services\Transaction;

use App\Entities\Transaction;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Services\Exceptions\RecordTransactionServiceException;
use Throwable;

class RecordTransactionService
{

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     */
    public function __construct(
        private TransactionRepositoryInterface $transactionRepository
    )
    {
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function recordTransaction(Transaction $transaction): void
    {
        try{
            $this->transactionRepository->create($transaction);
        } catch (Throwable $throwable) {
            throw new RecordTransactionServiceException(
                message: sprintf('Error recording transaction'),
                code: 500,
                previous: $throwable
            );
        }
    }
}
