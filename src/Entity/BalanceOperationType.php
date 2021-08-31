<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BalanceOperationType implements Translatable
{
    private UuidInterface $id;
    private string $title;
    private bool $income;
    private bool $necessary;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getTranslationType(): string
    {
        return self::class;
    }

    public function getValuesForTranslation(): array
    {
        return [
            'title' => $this->getTitle(),
        ];
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