<?php

declare(strict_types=1);

namespace Tests\Unit\Entities;

use App\Entities\Account;
use App\Entities\AccountFactory;
use App\ValueObjects\Exceptions\InvalidAccountIdException;
use App\ValueObjects\Exceptions\InvalidAmountException;
use PHPUnit\Framework\TestCase;

class AccountFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateValidAccount(): void
    {
        $account = AccountFactory::fromArray([
            'amount' => 100,
            'id' => 1
        ]);

        $expectedAccountArray = [
            'id' => 1,
            'amount' => 100
        ];

        $this->assertInstanceOf(Account::class, $account);
        $this->assertEquals($expectedAccountArray, $account->toArray());
    }

    /**
     * @return void
     */
    public function testInvalidAccountIdException(): void
    {
        $this->expectException(InvalidAccountIdException::class);

        AccountFactory::fromArray([
            'amount' => 100,
            'id' => 0
        ]);
    }

    /**
     * @return void
     */
    public function testInvalidAmountException(): void
    {
        $this->expectException(InvalidAmountException::class);

        AccountFactory::fromArray([
            'amount' => -10,
            'id' => 100
        ]);
    }
}
