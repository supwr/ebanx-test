<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\Entities\Account;

interface AccountRepositoryInterface
{
    public function resetAll(): void;
    public function getAccountById(int $id): ?Account;
    public function createAccount(Account $account): void;
    public function updateBalance(Account $account): void;
}
