<?php


namespace App\Tests\Unit\Service;


use App\DTO\UserRegistrationRequest;
use App\Entity\User;
use App\Event\UserEvents;
use App\Event\UserRegisteredEvent;
use App\Exception\UserExistsException;
use App\Repository\UserRepositoryInterface;
use App\Service\UserFactoryInterface;
use App\Service\UserRegistrationService;
use App\Service\UserRegistrationServiceInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserRegistrationServiceTest extends TestCase
{
    private UuidInterface $userId;
    private UserRepositoryInterface $userRepository;
    private UserFactoryInterface $userFactory;
    private EventDispatcherInterface $eventDispatcher;

    private UserRegistrationServiceInterface $userRegistrationService;

    public function setUp(): void
    {
        $uuidFactory = $this->createMock(UuidFactoryInterface::class);
        $uuidFactory->method('uuid4')
            ->willReturn(Uuid::fromString('7b85d60d-a620-465a-a30e-7353ecbb4a61'));

        Uuid::setFactory($uuidFactory);
        $this->userId = Uuid::uuid4();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userFactory = $this->createMock(UserFactoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->userRegistrationService = new UserRegistrationService(
            $this->userRepository,
            $this->userFactory,
            $this->eventDispatcher
        );
    }

    /**
     * @test
     */
    public function shouldCreateUserAndDispatchUserRegisteredEventWhenDataIsValid()
    {
        $registrationRequest = new UserRegistrationRequest();
        $registrationRequest->setEmail('test@example.com');
        $registrationRequest->setPassword('password');

        $this->userRepository->expects($this->once())
            ->method('findByLogin')
        ;

        $this->userRepository->expects($this->once())
            ->method('save')
        ;

        $this->userFactory->expects($this->once())
            ->method('createUser')
            ->willReturn(new User(Uuid::uuid4(), 'test@example.com', 'password'))
        ;

        $this->eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with(new UserRegisteredEvent($this->userId, 'test@example.com'), UserEvents::USER_REGISTERED_EVENT)
        ;

        $this->userRegistrationService->registerUser($registrationRequest);
    }

    /**
     * @test
     */
    public function shouldNotCreateUserWhenUserAlreadyExists()
    {
        $this->expectException(UserExistsException::class);

        $registrationRequest = new UserRegistrationRequest();
        $registrationRequest->setEmail('test@example.com');
        $registrationRequest->setPassword('password');

        $user = new User(Uuid::uuid4(), 'test@example.com', 'password');

        $this->userRepository->expects($this->once())
            ->method('findByLogin')
            ->with('test@example.com')
            ->willReturn($user)
        ;

        $this->userRepository->expects($this->never())
            ->method('save')
        ;

        $this->userFactory->expects($this->never())
            ->method('createUser')
        ;

        $this->eventDispatcher->expects($this->never())
            ->method('dispatch')
        ;

        $this->userRegistrationService->registerUser($registrationRequest);
    }
}