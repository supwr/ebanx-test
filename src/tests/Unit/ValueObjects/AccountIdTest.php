<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\AccountId;
use App\ValueObjects\Exceptions\InvalidAccountIdException;
use PHPUnit\Framework\TestCase;

class AccountIdTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateValidAccountId(): void
    {
        $accountId = AccountId::fromInt(1);

        $this->assertInstanceOf(AccountId::class, $accountId);
        $this->assertEquals(1, $accountId->toInt());
        $this->assertIsInt($accountId->toInt());
    }

    /**
     * @return void
     */
    public function testInvalidAccountId(): void
    {
        $this->expectException(InvalidAccountIdException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf('Invalid account id [%s]', 0));

        AccountId::fromInt(0);
    }
}
