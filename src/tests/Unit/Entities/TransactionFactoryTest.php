<?php

declare(strict_types=1);

namespace Tests\Unit\Entities;

use App\Entities\Transaction;
use App\Entities\TransactionFactory;
use App\ValueObjects\Exceptions\InvalidAccountIdException;
use App\ValueObjects\Exceptions\InvalidAmountException;
use App\ValueObjects\TransactionType;
use PHPUnit\Framework\TestCase;

class TransactionFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateValidTransaction(): void
    {
        $transaction = TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => null,
            'destination' => 100
        ]);

        $expectedTransactionArray = [
            'id' => null,
            'type' => TransactionType::DEPOSIT,
            'amount' => 100,
            'origin' => null,
            'destination' => 100,
            'created_at' => null
        ];

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals($expectedTransactionArray, $transaction->toArray());
    }

    /**
     * @return void
     */
    public function testInvalidOriginAccountIdException(): void
    {
        $this->expectException(InvalidAccountIdException::class);

        TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => 0,
            'destination' => 100
        ]);
    }

    /**
     * @return void
     */
    public function testInvalidDestinationAccountIdException(): void
    {
        $this->expectException(InvalidAccountIdException::class);

        TransactionFactory::fromArray([
            'amount' => 100,
            'type' => TransactionType::DEPOSIT,
            'origin' => 100,
            'destination' => 0
        ]);
    }

    /**
     * @return void
     */
    public function testInvalidAmountException(): void
    {
        $this->expectException(InvalidAmountException::class);

        TransactionFactory::fromArray([
            'amount' => -100,
            'type' => TransactionType::DEPOSIT,
            'origin' => 300,
            'destination' => 100
        ]);
    }
}
