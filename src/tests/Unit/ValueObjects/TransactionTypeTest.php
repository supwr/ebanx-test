<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Exceptions\InvalidTransactionTypeException;
use App\ValueObjects\TransactionType;
use PHPUnit\Framework\TestCase;

class TransactionTypeTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateValidTransactionType(): void
    {
        $transactionType = TransactionType::fromString(TransactionType::DEPOSIT);

        $this->assertInstanceOf(TransactionType::class, $transactionType);
        $this->assertIsString($transactionType->toString());
        $this->assertEquals(TransactionType::DEPOSIT, $transactionType->toString());
    }

    /**
     * @return void
     */
    public function testInvalidTransactionTypeException(): void
    {
        $this->expectException(InvalidTransactionTypeException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf('Invalid transaction type [%s]', 'invalid type'));

        TransactionType::fromString('invalid type');
    }
}
