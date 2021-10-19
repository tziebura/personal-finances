<?php

namespace App\ValueObject;

use Money\Money;

class OperationAmount
{
    private Money $money;
    private bool $income;

    public function __construct(Money $money, bool $income)
    {
        $this->money = $money;
        $this->income = $income;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function isIncome(): bool
    {
        return $this->income;
    }
}