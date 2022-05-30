<?php

declare(strict_types=1);

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidAmountException;

class Amount
{
    private float $amount;

    /**
     * @param float $amount
     * @throws InvalidAmountException
     */
    public function __construct(float $amount)
    {
        $this->setAmount($amount);
    }

    /**
     * @param float $amount
     * @return static
     */
    public static function fromFloat(float $amount): self
    {
        return new self($amount);
    }

    /**
     * @param float $amount
     * @return void
     * @throws InvalidAmountException
     */
    private function setAmount(float $amount): void
    {
        if (!$this->isValid($amount)) {
            throw new InvalidAmountException(
                'Invalid amount. It must be greater than zero'
            );
        }

        $this->amount = $amount;
    }

    /**
     * @param float $amount
     * @return bool
     */
    private function isValid(float $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        return true;
    }

    /**
     * @return float
     */
    public function toFloat(): float
    {
        return $this->amount;
    }
}
