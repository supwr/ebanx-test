<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Event;

use App\Entities\AccountFactory;
use App\Entities\TransactionFactory;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Repositories\Exceptions\AccountRepositoryException;
use App\Repositories\Exceptions\TransactionRepositoryException;
use App\Services\Event\CreateAccountService;
use App\Services\Exceptions\CreateAccountServiceException;
use App\Services\Exceptions\RecordTransactionServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\TransactionType;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateAccountServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateAccount(): void
    {
        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('createAccount');
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create');
        });

        $transactionService = new RecordTransactionService($transactionAccountRepository);

        $createAccountService = new CreateAccountService($mockAccountRepository, $transactionService);
        $this->assertNull($createAccountService->createAccount($transaction));
    }

    /**
     * @return void
     * @throws CreateAccountServiceException
     */
    public function testCreateServiceAccountRepositoryException():void
    {
        $this->expectException(CreateAccountServiceException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Error creating account');

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('createAccount')
                ->andThrow(new AccountRepositoryException(sprintf('Error creating account of id [%s]', 100)));
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create');
        });

        $transactionService = new RecordTransactionService($transactionAccountRepository);

        $createAccountService = new CreateAccountService($mockAccountRepository, $transactionService);
        $createAccountService->createAccount($transaction);
    }

    /**
     * @return void
     * @throws CreateAccountServiceException
     */
    public function testCreateServiceTransactionRepositoryException():void
    {
        $this->expectException(CreateAccountServiceException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Error creating account');

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $mockAccountRepository = Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('createAccount');
        });

        $transactionAccountRepository = Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create')
                ->andThrow(new TransactionRepositoryException('Error inserting new transaction'));
        });

        $transactionService = new RecordTransactionService($transactionAccountRepository);

        $createAccountService = new CreateAccountService($mockAccountRepository, $transactionService);
        $createAccountService->createAccount($transaction);
    }
}
