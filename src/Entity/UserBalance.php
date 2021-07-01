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
    private string $_currentBalanceCurrency;
    private Money $currentBalance;

    public function __construct(UuidInterface $id, User $user)
    {
        $this->id                      = $id;
        $this->user                    = $user;
        $this->_currentBalanceAmount   = 0;
        $this->_currentBalanceCurrency = 'PLN';
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
                new Currency($this->_currentBalanceCurrency)
            );
        }

        return $this->currentBalance;
    }
}