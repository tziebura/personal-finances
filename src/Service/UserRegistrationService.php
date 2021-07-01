<?php


namespace App\Service;


use App\DTO\UserRegistrationRequest;
use App\Event\UserEvents;
use App\Event\UserRegisteredEvent;
use App\Exception\UserExistsException;
use App\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserRegistrationService implements UserRegistrationServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private UserFactoryInterface $userFactory;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserFactoryInterface $userFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerUser(UserRegistrationRequest $request): void
    {
        $existingUser = $this->userRepository->findByLogin($request->getEmail());

        if ($existingUser) {
            throw new UserExistsException($request->getEmail());
        }

        $user = $this->userFactory->createUser(
            Uuid::uuid4(),
            $request->getEmail(),
            $request->getPassword()
        );

        $this->userRepository->save($user);

        $this->eventDispatcher->dispatch(new UserRegisteredEvent(
            $user->getId(),
            $user->getEmail()
        ), UserEvents::USER_REGISTERED_EVENT);
    }
}