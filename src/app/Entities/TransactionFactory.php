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
        $origin = self::getIntOrNull($data, 'origin');
        $destination = self::getIntOrNull($data, 'destination');
        $createdAt = !empty(self::getString($data, 'created_at'))
            ? new DateTimeImmutable(self::getString($data, 'created_at'))
            : null;

        return new Transaction(
            id: self::getIntOrNull($data, 'id'),
            type: TransactionType::fromString($transactionType),
            amount: Amount::fromFloat($amount),
            origin: !is_null($origin) ? AccountId::fromInt($origin) : null,
            destination: !is_null($destination) ? AccountId::fromInt($destination) : null,
            createdAt: $createdAt
        );
    }
}
