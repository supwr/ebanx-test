<?php

declare(strict_types=1);

namespace App\Entities;

use App\Utils\Mapping;
use App\ValueObjects\Amount;

class AccountFactory
{
    use Mapping;

    /**
     * @param array $data
     * @return Account
     */
    public static function fromArray(array $data): Account
    {
        $amount = self::getFloat($data, 'amount');

        return new Account(
            id: self::getInt($data, 'id'),
            amount: Amount::fromFloat($amount)
        );
    }
}
