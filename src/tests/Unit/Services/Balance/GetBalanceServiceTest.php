<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Balance;

use App\Entities\AccountFactory;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Balance\GetBalanceService;
use App\Services\Exceptions\BalanceNotFoundException;
use App\ValueObjects\Amount;
use Mockery;
use PHPUnit\Framework\TestCase;

class GetBalanceServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetBalance(): void
    {
        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
           $mock->shouldReceive('getAccountById')
            ->andReturn(AccountFactory::fromArray([
                'amount' => 100,
                'id' => 1
            ]));
        });

        $getBalanceService = new GetBalanceService($mockAccountRepository);
        $balance = $getBalanceService->getBalance(1);

        $this->assertInstanceOf(Amount::class, $balance);
        $this->assertEquals(100, $balance->toFloat());
    }

    /**
     * @return void
     */
    public function testBalanceNotFoundException(): void
    {
        $this->expectException(BalanceNotFoundException::class);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('getAccountById')
                ->andReturn(null);
        });

        $getBalanceService = new GetBalanceService($mockAccountRepository);
        $getBalanceService->getBalance(1);
    }
}
