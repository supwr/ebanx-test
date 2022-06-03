<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Account;

use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Repositories\Exceptions\AccountRepositoryException;
use App\Repositories\Exceptions\TransactionRepositoryException;
use App\Services\Account\ResetAccountBalanceService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResetAccountBalanceServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testResetAll(): void
    {
        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $mockTransactionRepository = Mockery::mock(TransactionRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $resetAccountService = new ResetAccountBalanceService($mockAccountRepository, $mockTransactionRepository);
        $this->assertNull($resetAccountService->reset());
    }

    /**
     * @return void
     */
    public function testResetAllAccountsRepositoryException(): void
    {
        $this->expectException(AccountRepositoryException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Error reseting accounts');

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll')
                ->andThrow(new AccountRepositoryException('Error reseting accounts'));
        });

        $mockTransactionRepository = Mockery::mock(TransactionRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $resetAccountService = new ResetAccountBalanceService($mockAccountRepository, $mockTransactionRepository);
        $resetAccountService->reset();
    }

    /**
     * @return void
     */
    public function testResetAllTransactionsRepositoryException(): void
    {
        $this->expectException(TransactionRepositoryException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Error reseting transactions');

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $mockTransactionRepository = Mockery::mock(TransactionRepositoryInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll')
                ->andThrow(new TransactionRepositoryException('Error reseting transactions'));
        });

        $resetAccountService = new ResetAccountBalanceService($mockAccountRepository, $mockTransactionRepository);
        $resetAccountService->reset();
    }
}
