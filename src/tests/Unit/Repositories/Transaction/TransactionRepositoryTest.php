<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories\Transaction;

use App\Entities\TransactionFactory;
use App\Models\Transaction as TransactionModel;
use App\Repositories\Exceptions\TransactionRepositoryException;
use App\Repositories\Transaction\TransactionRepository;
use App\ValueObjects\TransactionType;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransactionRepositoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateTransaction(): void
    {
        $transactionToReturn = new TransactionModel();
        $transactionAsArray = [
            'type' => TransactionType::WITHDRAW,
            'amount' => 100.0,
            'origin' => 1,
            'destination' => null
        ];

        $transactionEntity = TransactionFactory::fromArray($transactionAsArray);
        $transactionToReturn->fill($transactionAsArray);

        $mockedModel = Mockery::mock(TransactionModel::class, function(Mockery\MockInterface $mock) use ($transactionToReturn){
            $mock->shouldReceive('create')
                ->andReturn($transactionToReturn);
        });

        $transactionRepository = new TransactionRepository($mockedModel);
        $this->assertNull($transactionRepository->create($transactionEntity));
    }

    /**
     * @return void
     * @throws TransactionRepositoryException
     */
    public function testCreateTransactionRepositoryException(): void
    {
        $this->expectException(TransactionRepositoryException::class);
        $this->expectExceptionMessage('Error inserting new transaction');
        $this->expectExceptionCode(500);

        $transactionToReturn = new TransactionModel();
        $transactionAsArray = [
            'type' => TransactionType::WITHDRAW,
            'amount' => 100.0,
            'origin' => 1,
            'destination' => null
        ];

        $transactionEntity = TransactionFactory::fromArray($transactionAsArray);
        $transactionToReturn->fill($transactionAsArray);

        $mockedModel = Mockery::mock(TransactionModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('create')
                ->andThrow(new Exception('Connection error'));
        });

        $transactionRepository = new TransactionRepository($mockedModel);
        $transactionRepository->create($transactionEntity);
    }

    /**
     * @return void
     */
    public function testResetAll(): void
    {
        $mockedModel = Mockery::mock(TransactionModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('truncate')
                ->andReturn(true);
        });

        $transactionRepository = new TransactionRepository($mockedModel);
        $this->assertNull($transactionRepository->resetAll());
    }

    /**
     * @return void
     */
    public function testResetAllTransactionRepositoryException(): void
    {
        $this->expectException(TransactionRepositoryException::class);
        $this->expectExceptionMessage('Error reseting transactions');
        $this->expectExceptionCode(500);

        $mockedModel = Mockery::mock(TransactionModel::class, function(Mockery\MockInterface $mock) {
            $mock->shouldReceive('truncate')
                ->andThrow(new Exception('Connection error'));
        });

        $transactionRepository = new TransactionRepository($mockedModel);
        $this->assertNull($transactionRepository->resetAll());
    }
}
