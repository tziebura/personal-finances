<?php


namespace App\EventSubscriber;


use App\Entity\UserBalance;
use App\Event\UserRegisteredEvent;
use App\Repository\UserBalanceRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredEventSubscriber implements EventSubscriberInterface
{
    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalanceRepository;

    public function __construct(UserRepositoryInterface $userRepository, UserBalanceRepositoryInterface $userBalanceRepository)
    {
        $this->userRepository = $userRepository;
        $this->userBalanceRepository = $userBalanceRepository;
    }


    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::class => [
                'onUserRegisteredEvent'
            ]
        ];
    }

    public function onUserRegisteredEvent(UserRegisteredEvent $event)
    {
        $user = $this->userRepository->findByLogin($event->getEmail());

        if (!$user) {
            return;
        }

        $userBalance = $this->userBalanceRepository->findUsersBalance($user);

        if ($userBalance) {
            return;
        }

        $userBalance = new UserBalance(
            Uuid::uuid4(),
            $user
        );

        $this->userBalanceRepository->save($userBalance);
    }
}