<?php

namespace App\EventSubscriber;

use App\Event\BalanceOperationEvents;
use App\Event\BalanceOperationUpdatedEvent;
use App\Repository\UserBalanceRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BalanceOperationUpdatedEventSubscriber implements EventSubscriberInterface
{
    private UserBalanceRepositoryInterface $userBalanceRepository;

    public function __construct(UserBalanceRepositoryInterface $userBalanceRepository)
    {
        $this->userBalanceRepository = $userBalanceRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BalanceOperationEvents::BALANCE_OPERATION_UPDATED => [
                'onBalanceOperationUpdated'
            ]
        ];
    }

    public function onBalanceOperationUpdated(BalanceOperationUpdatedEvent $event): void
    {
        $newOperationAmount = $event->getNewOperationAmount();
        $oldOperationAmount = $event->getOldOperationAmount();

        if ($newOperationAmount->isIncome() === $oldOperationAmount->isIncome() && $newOperationAmount->getMoney()->equals($oldOperationAmount->getMoney())) {
            return;
        }

        $balance = $this->userBalanceRepository->findUsersBalance($event->getUser());

        if ($oldOperationAmount->isIncome()) {
            $balance->subtractBalance($oldOperationAmount->getMoney());
        } else {
            $balance->addBalance($oldOperationAmount->getMoney());
        }

        if ($newOperationAmount->isIncome()) {
            $balance->addBalance($newOperationAmount->getMoney());
        } else {
            $balance->subtractBalance($newOperationAmount->getMoney());
        }

        $this->userBalanceRepository->save($balance);
    }
}