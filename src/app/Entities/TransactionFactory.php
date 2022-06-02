<?php

declare(strict_types=1);

namespace App\Entities;

use App\Utils\Mapping;
use App\ValueObjects\AccountId;
use App\ValueObjects\Amount;
use App\ValueObjects\TransactionType;
use DateTimeImmutable;

class TransactionFactory
{
    use Mapping;

    public static function fromArray(array $data): Transaction
    {
        $amount = self::getFloat($data, 'amount');
        $transactionType = self::getString($data, 'type');
        $origin = self::getInt($data, 'origin');
        $destination = self::getInt($data, 'destination');
        $createdAt = !empty(self::getString($data, 'created_at'))
            ? new DateTimeImmutable(self::getString($data, 'created_at'))
            : null;

        return new Transaction(
            id: self::getIntOrNull($data, 'id'),
            type: TransactionType::fromString($transactionType),
            amount: Amount::fromFloat($amount),
            origin: AccountId::fromInt($origin),
            destination: AccountId::fromInt($destination),
            createdAt: $createdAt
        );
    }
}
