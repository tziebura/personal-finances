<?php


namespace App\Entity;


use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Money\Money;
use Money\Currency;

class UserBalance
{
    use Timestamps;

    private UuidInterface $id;
    private User $user;
    private string $_currentBalanceAmount;
    private Money $currentBalance;

    public function __construct(UuidInterface $id, User $user)
    {
        $this->id                      = $id;
        $this->user                    = $user;
        $this->_currentBalanceAmount   = 0;
        $this->createdAt               = new DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCurrentBalance(): Money
    {
        if (!isset($this->currentBalance)) {
            $this->currentBalance = new Money(
                $this->_currentBalanceAmount,
                new Currency('PLN')
            );
        }

        return $this->currentBalance;
    }

    public function addBalance(Money $money): void
    {
        $this->setCurrentBalance(
            $this->currentBalance = $this->getCurrentBalance()->add($money)
        );
    }

    public function subtractBalance(Money $money): void
    {
        $this->setCurrentBalance(
            $this->getCurrentBalance()->subtract($money)
        );
    }

    public function onUpdate()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    protected function setCurrentBalance(Money $money): void
    {
        $this->currentBalance = $money;
        $this->_currentBalanceAmount = $money->getAmount();
    }
}