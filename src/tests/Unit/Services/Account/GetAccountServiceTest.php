<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Account;

use App\Entities\Account;
use App\Entities\AccountFactory;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Services\Account\GetAccountService;
use App\Services\Exceptions\BalanceNotFoundException;
use App\ValueObjects\Amount;
use Mockery;
use PHPUnit\Framework\TestCase;

class GetAccountServiceTest extends TestCase
{
    private ?Account $targetAccount;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->targetAccount = AccountFactory::fromArray([
            'id' => 100,
            'amount' => 300,
        ]);

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return void
     */
    public function testGetAccountById(): void
    {
        $mockAccountRepository = $this->mockAccountRepositoryInterface();

        $getAccountService = new GetAccountService($mockAccountRepository);
        $account = $getAccountService->getAccountById(100);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($this->targetAccount->toArray(), $account->toArray());
    }

    /**
     * @return void
     * @throws \App\Services\Exceptions\BalanceNotFoundException
     */
    public function testGetBalance(): void
    {
        $mockAccountRepository = $this->mockAccountRepositoryInterface();

        $getAccountService = new GetAccountService($mockAccountRepository);
        $amount = $getAccountService->getBalance(100);

        $this->assertInstanceOf(Amount::class, $amount);
        $this->assertEquals($this->targetAccount->amount->toFloat(), $amount->toFloat());
    }

    /**
     * @return void
     * @throws BalanceNotFoundException
     */
    public function testGetBalanceNotFoundException(): void
    {
        $this->expectException(BalanceNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Balance of id [%s] not found', 100));
        $this->expectExceptionCode(404);

        $this->targetAccount = null;
        $mockAccountRepository = $this->mockAccountRepositoryInterface();

        $getAccountService = new GetAccountService($mockAccountRepository);
        $getAccountService->getBalance(100);
    }

    /**
     * @return Mockery\MockInterface|AccountRepositoryInterface
     */
    private function mockAccountRepositoryInterface(): Mockery\MockInterface|AccountRepositoryInterface
    {
        $targetAccount = $this->targetAccount;

        return Mockery::mock(AccountRepositoryInterface::class, function (Mockery\MockInterface $mock) use ($targetAccount) {
            $mock->shouldReceive('getAccountById')
                ->andReturn($targetAccount);
        });
    }
}
