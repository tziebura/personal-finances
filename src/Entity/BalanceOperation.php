<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

class BalanceOperation
{
    use Timestamps;

    private UuidInterface $id;
    private string $title;
    private \Money\Money $amount;
    private BalanceOperationType $type;

    public function __construct(UuidInterface $id, string $title, \Money\Money $amount, BalanceOperationType $type)
    {
        $this->id = $id;
        $this->title = $title;
        $this->amount = $amount;
        $this->type = $type;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAmount(): \Money\Money
    {
        return $this->amount;
    }

    public function getType(): BalanceOperationType
    {
        return $this->type;
    }

}