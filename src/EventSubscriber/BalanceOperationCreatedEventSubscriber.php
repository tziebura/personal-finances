<?php

namespace App\EventSubscriber;

use App\Event\BalanceOperationCreatedEvent;
use App\Event\BalanceOperationEvents;
use App\Repository\UserBalanceRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BalanceOperationCreatedEventSubscriber implements EventSubscriberInterface
{
    private UserBalanceRepositoryInterface $userBalanceRepository;

    public function __construct(UserBalanceRepositoryInterface $userBalanceRepository)
    {
        $this->userBalanceRepository = $userBalanceRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BalanceOperationEvents::BALANCE_OPERATION_CREATED => [
                'onBalanceOperationCreated'
            ]
        ];
    }

    public function onBalanceOperationCreated(BalanceOperationCreatedEvent $event): void
    {
        $balance = $this->userBalanceRepository->findUsersBalance($event->getUser());
        $operationAmount = $event->getOperationAmount();

        if ($operationAmount->isIncome()) {
            $balance->addBalance($operationAmount->getMoney());
        } else {
            $balance->subtractBalance($operationAmount->getMoney());
        }

        $this->userBalanceRepository->save($balance);
    }
}