<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidTransactionTypeException;

class TransactionType
{
    public const WITHDRAW = 'withdraw';
    public const TRANSFER = 'transfer';
    public const DEPOSIT = 'deposit';

    public const VALID_TYPES = [
        self::WITHDRAW,
        self::TRANSFER,
        self::DEPOSIT,
    ];

    private string $type;

    public function __construct(string $type)
    {
        $this->setType($type);
    }

    /**
     * @param string $type
     * @return static
     */
    public static function fromString(string $type): self
    {
        return new self($type);
    }

    /**
     * @param string $type
     * @return void
     * @throws InvalidTransactionTypeException
     */
    private function setType(string $type): void
    {
        if (!$this->isValid($type)) {
            throw new InvalidTransactionTypeException(
                sprintf('Invalid transaction type [%s]', $type)
            );
        }

        $this->type = $type;
    }

    /**
     * @param string $type
     * @return bool
     */
    private function isValid(string $type): bool
    {
        return in_array($type, self::VALID_TYPES);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->type;
    }
}
