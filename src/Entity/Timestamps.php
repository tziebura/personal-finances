<?php


namespace App\Entity;


use DateTimeImmutable;

trait Timestamps
{
    protected DateTimeImmutable $createdAt;

    protected ?DateTimeImmutable $updatedAt;

    public function onUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}