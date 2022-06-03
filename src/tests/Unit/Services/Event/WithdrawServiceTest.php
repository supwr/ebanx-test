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
use App\Services\Event\DepositService;
use App\Services\Event\WithdrawService;
use App\Services\Exceptions\AccountNotFoundException;
use App\Services\Exceptions\WithdrawServiceException;
use App\Services\Transaction\RecordTransactionService;
use App\ValueObjects\TransactionType;
use Mockery;
use PHPUnit\Framework\TestCase;

class WithdrawServiceTest extends TestCase
{
    private bool $mockAccount = false;
    private bool $updateBalanceException = false;

    /**
     * @return void
     */
    public function testWithdrawFromExistingAccount(): void
    {
        $this->mockAccount = true;

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::WITHDRAW,
            'origin' => 100,
            'destination' => null
        ]);

        $withdrawService = $this->makeWithdrawService();
        $this->assertNull($withdrawService->withdraw($transaction));
    }

    /**
     * @return void
     * @throws AccountNotFoundException
     * @throws \App\Services\Exceptions\WithdrawServiceException
     */
    public function testWithdrawAccountNotFoundException(): void
    {
        $this->expectException(AccountNotFoundException::class);
        $this->expectExceptionMessage(sprintf('Account of id [%s] not found', 100));
        $this->expectExceptionCode(404);

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::WITHDRAW,
            'origin' => 100,
            'destination' => null
        ]);

        $withdrawService = $this->makeWithdrawService();
        $withdrawService->withdraw($transaction);
    }

    /**
     * @return void
     * @throws AccountNotFoundException
     * @throws WithdrawServiceException
     */
    public function testWithdrawNotEnoughFundsException(): void
    {
        $this->mockAccount = true;

        $this->expectException(WithdrawServiceException::class);
        $this->expectExceptionMessage(sprintf(
            'You don\'t have enough funds [balance: %d]
                    to withdraw [amount: %d]',
            100,
            200
        ));
        $this->expectExceptionCode(400);

        $transaction = TransactionFactory::fromArray([
            'amount' => 200,
            'type' => TransactionType::WITHDRAW,
            'origin' => 100,
            'destination' => null
        ]);

        $withdrawService = $this->makeWithdrawService();
        $withdrawService->withdraw($transaction);
    }

    /**
     * @return void
     * @throws AccountNotFoundException
     * @throws WithdrawServiceException
     */
    public function testWithdrawServiceException(): void
    {
        $this->mockAccount = true;
        $this->updateBalanceException = true;

        $this->expectException(WithdrawServiceException::class);
        $this->expectExceptionMessage(sprintf('Error executing withdraw to account of id [%s]', 100));
        $this->expectExceptionCode(500);

        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::WITHDRAW,
            'origin' => 100,
            'destination' => null
        ]);

        $withdrawService = $this->makeWithdrawService();
        $withdrawService->withdraw($transaction);
    }

    /**
     * @return WithdrawService
     */
    private function makeWithdrawService(): WithdrawService
    {
        $recordTransactionService = new RecordTransactionService($this->mockTransactionRepository());

        $dependencies = [
            new GetAccountService($this->mockAccountRepository()),
            new UpdateAccountBalanceService($this->mockAccountRepository()),
            $recordTransactionService,
        ];

        return new WithdrawService(...$dependencies);
    }

    /**
     * @return Mockery\MockInterface|AccountRepositoryInterface
     */
    private function mockAccountRepository(): Mockery\MockInterface|AccountRepositoryInterface
    {
        $account = $this->mockAccount ? self::generateAccount() : null;
        $updateBalanceException = $this->updateBalanceException;

        return Mockery::mock(AccountRepositoryInterface::class, function (Mockery\MockInterface $mock) use ($account, $updateBalanceException) {
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
     * @return Mockery\MockInterface|TransactionRepositoryInterface
     */
    private function mockTransactionRepository(): Mockery\MockInterface|TransactionRepositoryInterface
    {
        return Mockery::mock(TransactionRepositoryInterface::class, function (Mockery\MockInterface $mock) {
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
