<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidAccountIdException;

class AccountId
{
    private int $id;

    /**
     * @param int $id
     * @throws InvalidAccountIdException
     */
    public function __construct(int $id)
    {
        $this->setId($id);
    }

    /**
     * @param int $id
     * @return static
     */
    public static function fromInt(int $id): self
    {
        return new self($id);
    }

    /**
     * @param int $id
     * @return void
     * @throws InvalidAccountIdException
     */
    private function setId(int $id): void
    {
        if (!$this->isValid($id)) {
            throw new InvalidAccountIdException(
                sprintf('Invalid account id [%s]', $id)
            );
        }

        $this->id = $id;
    }

    /**
     * @param int $id
     * @return bool
     */
    private function isValid(int $id): bool
    {
        return $id > 0;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->id;
    }
}
