<?php

namespace App\Providers;

use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AccountRepositoryInterface::class,
            AccountRepository::class
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
    }
}
