<?php

namespace App\Event;

use App\Entity\User;
use App\ValueObject\OperationAmount;

class BalanceOperationDeletedEvent
{
    private OperationAmount $operationAmount;
    private User $user;

    public function __construct(OperationAmount $operationAmount, User $user)
    {
        $this->operationAmount = $operationAmount;
        $this->user = $user;
    }

    public function getOperationAmount(): OperationAmount
    {
        return $this->operationAmount;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}