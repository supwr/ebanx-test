<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Event;

use App\Entities\Account;
use App\Entities\AccountFactory;
use App\Entities\TransactionFactory;
use App\Interfaces\Repositories\AccountRepositoryInterface;
use App\Interfaces\Repositories\TransactionRepositoryInterface;
use App\Repositories\Exceptions\AccountRepositoryException;
use App\Services\Account\GetAccountService;
use App\Services\Account\UpdateAccountBalanceService;
use App\Services\Event\CreateAccountService;
use App\Services\Event\DepositService;
use App\Services\Exceptions\DepositServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\TransactionType;
use Mockery;
use PHPUnit\Framework\TestCase;

class DepositServiceTest extends TestCase
{
    private bool $mockAccount = false;
    private bool $updateBalanceException = false;

    /**
     * @return void
     * @throws \App\Services\Exceptions\DepositServiceException
     */
    public function testMakeDeposit(): void
    {
        $this->mockAccount = true;

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $depositService = $this->makeDepositService();
        $this->assertNull($depositService->deposit($transaction));
    }

    /**
     * @return void
     * @throws \App\Services\Exceptions\DepositServiceException
     */
    public function testCreateAcount(): void
    {
        $this->mockAccount = false;

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $depositService = $this->makeDepositService();
        $this->assertNull($depositService->deposit($transaction));
    }

//    /**
//     * @return void
//     * @throws \App\Services\Exceptions\DepositServiceException
//     */
//    public function testDepositServiceException(): void
//    {
//        $this->expectException(DepositServiceException::class);
//        $this->expectExceptionCode(500);
//        $this->expectExceptionMessage('Error making deposit');
//
//        $this->updateBalanceException = true;
//
//        $transaction = TransactionFactory::fromArray([
//            'amount' => 100,
//            'type' => TransactionType::DEPOSIT,
//            'origin' => null,
//            'destination' => 100
//        ]);
//
//        $depositService = $this->makeDepositService();
//        $depositService->deposit($transaction);
//    }

    /**
     * @return DepositService
     */
    private function makeDepositService(): DepositService
    {
        $recordTransactionService = new RecordTransactionService($this->mockTransactionRepository());

        $dependencies = [
            new GetAccountService($this->mockAccountRepository()),
            new UpdateAccountBalanceService($this->mockAccountRepository()),
            $recordTransactionService,
            new CreateAccountService($this->mockAccountRepository(), $recordTransactionService),
        ];

        return new DepositService(...$dependencies);
    }

    /**
     * @return Mockery\MockInterface
     */
    private function mockAccountRepository(): Mockery\MockInterface
    {
        $account = $this->mockAccount ? self::generateAccount() : null;
        $updateBalanceException = $this->updateBalanceException;

        return Mockery::mock(AccountRepositoryInterface::class, function(Mockery\MockInterface $mock) use ($account, $updateBalanceException){
            $mock->shouldReceive('getAccountById')
                ->andReturn($account);

            if ($updateBalanceException) {
                $mock->shouldReceive('updateBalance')
                    ->andThrow(new AccountRepositoryException());
            } else {
                $mock->shouldReceive('updateBalance');
            }

            $mock->shouldReceive('createAccount');
        });
    }

    /**
     * @return Mockery\MockInterface
     */
    private function mockTransactionRepository(): Mockery\MockInterface
    {
        return Mockery::mock(TransactionRepositoryInterface::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create');
        });
    }

    /**
     * @return Account
     */
    private static function generateAccount(): Account
    {
        return AccountFactory::fromArray([
            'amount' => 100,
            'id' => 1
        ]);
    }
}
