<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

interface TransactionRepositoryInterface
{
    public function create(): void;
}
