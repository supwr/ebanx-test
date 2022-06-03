<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Amount;
use App\ValueObjects\Exceptions\InvalidAmountException;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateValidAmount(): void
    {
        $amount = Amount::fromFloat(100);

        $this->assertInstanceOf(Amount::class, $amount);
        $this->assertEquals(100, $amount->toFloat());
        $this->assertIsFloat($amount->toFloat());
    }

    /**
     * @return void
     */
    public function testInvalidAmount(): void
    {
        $this->expectException(InvalidAmountException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('Invalid amount. It must be greater than zero');

        Amount::fromFloat(-100);
    }
}
