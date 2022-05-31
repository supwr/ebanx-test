<?php

declare(strict_types=1);

namespace App\Repositories\Transaction;

use App\Entities\Transaction;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Models\Transaction as TransactionModel;
use App\Repositories\Exceptions\TransactionRepositoryException;
use Throwable;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @param TransactionModel $model
     */
    public function __construct(private TransactionModel $model)
    {

    }

    /**
     * @param Transaction $transaction
     * @return void
     * @throws TransactionRepositoryException
     */
    public function create(Transaction $transaction): void
    {
        try {
            TransactionModel::create([
                'type' => $transaction->type->toString(),
                'amount' => $transaction->amount->toFloat(),
                'origin' => $transaction->origin->id,
                'destination' => $transaction->destination->id
            ]);
        } catch(Throwable $throwable) {
            throw new TransactionRepositoryException(
                message: 'Error inserting new transaction',
                code: 500,
                previous: $throwable
            );
        }
    }

    /**
     * @return void
     * @throws TransactionRepositoryException
     */
    public function resetAll(): void
    {
        try {
            TransactionModel::truncate();
        } catch(Throwable $throwable) {
            throw new TransactionRepositoryException(
                message: 'Error reseting transactions',
                code: 500,
                previous: $throwable
            );
        }
    }
}
