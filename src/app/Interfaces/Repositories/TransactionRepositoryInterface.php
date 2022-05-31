<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Entities\Transaction;

interface TransactionRepositoryInterface
{
    public function create(Transaction $transaction): void;
    public function resetAll(): void;
}
