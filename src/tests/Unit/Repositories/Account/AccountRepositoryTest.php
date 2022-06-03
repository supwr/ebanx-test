<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories\Account;

use App\Entities\Account;
use App\Entities\AccountFactory;
use App\Models\Account as AccountModel;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Exceptions\AccountRepositoryException;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class AccountRepositoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetAccountById(): void
    {
        $accountToReturn = new AccountModel();
        $accountAsArray = [
            'id' => 1,
            'amount' => 100.0
        ];
        $accountToReturn->fill($accountAsArray);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) use ($accountToReturn) {
            $mock->shouldReceive('find')
                ->andReturn($accountToReturn);
        });

        $accountRepository = new AccountRepository($mockedModel);
        $account = $accountRepository->getAccountById(1);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($accountAsArray, $account->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyAccountById(): void
    {
        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('find')
                ->andReturn(null);
        });

        $accountRepository = new AccountRepository($mockedModel);
        $account = $accountRepository->getAccountById(1);

        $this->assertNull($account);
    }

    /**
     * @return void
     */
    public function testCreateAccount(): void
    {
        $accountToReturn = new AccountModel();
        $accountAsArray = [
            'id' => 1,
            'amount' => 100.0
        ];

        $accountEntity = AccountFactory::fromArray($accountAsArray);
        $accountToReturn->fill($accountAsArray);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) use ($accountToReturn){
            $mock->shouldReceive('create')
                ->andReturn($accountToReturn);
        });

        $accountRepository = new AccountRepository($mockedModel);
        $this->assertNull($accountRepository->createAccount($accountEntity));
    }

    /**
     * @return void
     * @throws AccountRepositoryException
     */
    public function testAccountRepositoryException(): void
    {
        $this->expectException(AccountRepositoryException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage(sprintf('Error creating account of id [%s]', 1));

        $accountEntity = AccountFactory::fromArray([
            'id' => 1,
            'amount' => 100.0
        ]);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create')
                ->andThrow(new Exception('Connection error'));
        });

        $accountRepository = new AccountRepository($mockedModel);
        $accountRepository->createAccount($accountEntity);
    }

    /**
     * @return void
     * @throws AccountRepositoryException
     */
    public function testUpdateBalance(): void
    {
        $accountToReturn = new AccountModel();
        $accountAsArray = [
            'id' => 1,
            'amount' => 100.0
        ];

        $accountEntity = AccountFactory::fromArray($accountAsArray);
        $accountToReturn->fill($accountAsArray);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) use ($accountToReturn) {
            $mock->shouldReceive('find')
                ->andReturn($accountToReturn);

            $mock->shouldReceive('update')
                ->andReturn(true);
        });

        $accountRepository = new AccountRepository($mockedModel);
        $this->assertNull($accountRepository->updateBalance($accountEntity));
    }

    /**
     * @return void
     * @throws AccountRepositoryException
     */
    public function testUpdateBalanceAccountRepositoryException(): void
    {
        $this->expectException(AccountRepositoryException::class);

        $accountToReturn = new AccountModel();
        $accountAsArray = [
            'id' => 1,
            'amount' => 100.0
        ];

        $accountEntity = AccountFactory::fromArray($accountAsArray);
        $accountToReturn->fill($accountAsArray);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('update')
                ->andThrow(new Exception('Connection error'));
        });

        $accountRepository = new AccountRepository($mockedModel);
        $accountRepository->updateBalance($accountEntity);
    }

    /**
     * @return void
     */
    public function testResetAll(): void
    {
        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('truncate')
                ->andReturn(true);
        });

        $accountRepository = new AccountRepository($mockedModel);
        $this->assertNull($accountRepository->resetAll());
    }

    /**
     * @return void
     */
    public function testResetAllAccountRepositoryException(): void
    {
        $this->expectException(AccountRepositoryException::class);
        $this->expectExceptionMessage('Error reseting accounts');
        $this->expectExceptionCode(500);

        $mockedModel = Mockery::mock(AccountModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('truncate')
                ->andThrow(new Exception('Connection error'));
        });

        $accountRepository = new AccountRepository($mockedModel);
        $this->assertNull($accountRepository->resetAll());
    }
}
