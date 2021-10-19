<?php

namespace App\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BalanceOperationType
{
    use Timestamps;

    private UuidInterface $id;
    private string $title;
    private bool $income;
    private bool $necessary;

    public function __toString(): string
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->income = false;
        $this->necessary = false;
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

    public function isIncome(): bool
    {
        return $this->income ?? false;
    }

    public function setIncome(bool $income): void
    {
        $this->income = $income;
    }

    public function isNecessary(): bool
    {
        return $this->necessary;
    }

    public function setNecessary(bool $necessary): void
    {
        $this->necessary = $necessary;
    }
}