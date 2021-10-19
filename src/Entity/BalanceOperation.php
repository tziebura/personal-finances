<?php

namespace App\Entity;

use App\ValueObject\OperationAmount;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BalanceOperation
{
    use Timestamps;

    private UuidInterface $id;
    private string $title;
    private float $amount;
    private BalanceOperationType $type;
    private DateTimeImmutable $operationDate;
    private OperationAmount $_operationAmount;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAmount(): float
    {
        return $this->amount ?? 0;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
        unset($this->_operationAmount);
    }

    public function getType(): ?BalanceOperationType
    {
        return $this->type ?? null;
    }

    public function setType(BalanceOperationType $type): void
    {
        $this->type = $type;
        unset($this->_operationAmount);
    }

    public function getOperationDate(): ?DateTimeImmutable
    {
        return $this->operationDate ?? null;
    }

    public function setOperationDate(DateTimeImmutable $operationDate): void
    {
        $this->operationDate = $operationDate;
    }

    public function getOperationAmount(): OperationAmount
    {
        if (!isset($this->_operationAmount)) {
            $this->_operationAmount = new OperationAmount(
                new \Money\Money($this->amount * 100, new \Money\Currency('PLN')),
                $this->type->isIncome() ?? false
            );
        }
        return $this->_operationAmount;
    }
}