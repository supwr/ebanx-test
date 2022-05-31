<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

interface AccountRepositoryInterface
{
    public function resetAll(): void;
}
