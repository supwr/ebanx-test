<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Balance;

use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Repositories\Exceptions\AccountRepositoryException;
use App\Repositories\Exceptions\TransactionRepositoryException;
use App\Services\Balance\ResetBalanceService;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResetBalanceServiceServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testResetBalance(): void
    {
        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $resetBalanceService = new ResetBalanceService($mockAccountRepository, $transactionAccountRepository);

        $this->assertNull($resetBalanceService->reset());
    }

    /**
     * @return void
     */
    public function testAccountRepositoryException(): void
    {
        $this->expectException(AccountRepositoryException::class);
        $this->expectExceptionMessage('Error reseting accounts');
        $this->expectExceptionCode(500);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll')
                ->andThrow(new AccountRepositoryException('Error reseting accounts'));
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class);
        $resetBalanceService = new ResetBalanceService($mockAccountRepository, $transactionAccountRepository);
        $resetBalanceService->reset();
    }

    /**
     * @return void
     */
    public function testTransactionRepositoryException(): void
    {
        $this->expectException(TransactionRepositoryException::class);
        $this->expectExceptionMessage('Transaction Repository Exception');
        $this->expectExceptionCode(500);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll');
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('resetAll')
                ->andThrow(new TransactionRepositoryException('Transaction Repository Exception'));
        });
        $resetBalanceService = new ResetBalanceService($mockAccountRepository, $transactionAccountRepository);
        $resetBalanceService->reset();
    }
}
