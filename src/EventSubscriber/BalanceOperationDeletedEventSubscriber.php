<?php

namespace App\EventSubscriber;

use App\Event\BalanceOperationDeletedEvent;
use App\Event\BalanceOperationEvents;
use App\Repository\UserBalanceRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BalanceOperationDeletedEventSubscriber implements EventSubscriberInterface
{
    private UserBalanceRepositoryInterface $userBalanceRepository;

    public function __construct(UserBalanceRepositoryInterface $userBalanceRepository)
    {
        $this->userBalanceRepository = $userBalanceRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BalanceOperationEvents::BALANCE_OPERATION_DELETED => [
                'onBalanceOperationDeleted'
            ]
        ];
    }

    public function onBalanceOperationDeleted(BalanceOperationDeletedEvent $event): void
    {
        $balance = $this->userBalanceRepository->findUsersBalance($event->getUser());
        $operationAmount = $event->getOperationAmount();

        if ($operationAmount->isIncome()) {
            $balance->subtractBalance($operationAmount->getMoney());
        } else {
            $balance->addBalance($operationAmount->getMoney());
        }

        $this->userBalanceRepository->save($balance);
    }
}