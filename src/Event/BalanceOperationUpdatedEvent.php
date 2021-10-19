<?php

namespace App\Event;

use App\Entity\User;
use App\ValueObject\OperationAmount;

class BalanceOperationUpdatedEvent
{
    private OperationAmount $oldOperationAmount;
    private OperationAmount $newOperationAmount;
    private User $user;

    public function __construct(OperationAmount $oldOperationAmount, OperationAmount $newOperationAmount, User $user)
    {
        $this->oldOperationAmount = $oldOperationAmount;
        $this->newOperationAmount = $newOperationAmount;
        $this->user = $user;
    }

    public function getOldOperationAmount(): OperationAmount
    {
        return $this->oldOperationAmount;
    }

    public function getNewOperationAmount(): OperationAmount
    {
        return $this->newOperationAmount;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}