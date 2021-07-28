<?php


namespace App\Tests\Unit\EventSubscriber;


use App\Entity\UserBalance;
use App\Event\UserEvents;
use App\Event\UserRegisteredEvent;
use App\EventSubscriber\UserRegisteredEventSubscriber;
use App\Repository\UserBalanceRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Tests\Helpers\User;
use Monolog\Test\TestCase;
use Ramsey\Uuid\Uuid;

class UserRegisteredEventSubscriberTest extends TestCase
{
    use User;

    private UserRepositoryInterface $userRepository;
    private UserBalanceRepositoryInterface $userBalanceRepository;

    private UserRegisteredEventSubscriber $eventSubscriber;
    private UserRegisteredEvent $userRegisteredEvent;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userBalanceRepository = $this->createMock(UserBalanceRepositoryInterface::class);

        $this->eventSubscriber = new UserRegisteredEventSubscriber(
            $this->userRepository,
            $this->userBalanceRepository
        );

        $this->userRegisteredEvent = new UserRegisteredEvent(
            '1',
            'test@example.com'
        );
    }

    /**
     * @test
     */
    public function shouldSubscribeUserRegisteredEvent()
    {
        $expected = [
            UserEvents::USER_REGISTERED_EVENT => [
                'onUserRegisteredEvent'
            ]
        ];

        $this->assertEquals($expected, UserRegisteredEventSubscriber::getSubscribedEvents());
    }

    /**
     * @test
     */
    public function shouldNotCreateUserBalanceWhenUserDoesNotExist()
    {
        $this->userRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with($this->userRegisteredEvent->getEmail())
            ->willReturn(null)
        ;

        $this->userBalanceRepository->expects($this->never())
            ->method('findUsersBalance')
        ;

        $this->userBalanceRepository->expects($this->never())
            ->method('save')
        ;

        $this->eventSubscriber->onUserRegisteredEvent($this->userRegisteredEvent);
    }

    /**
     * @test
     */
    public function shouldNotCreateUserBalanceWhenUserAlreadyHasUserBalance()
    {
        $user = $this->getUserInstance();
        $userBalance = new UserBalance(Uuid::uuid4(), $user);

        $this->userRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with($this->userRegisteredEvent->getEmail())
            ->willReturn($user)
        ;

        $this->userBalanceRepository->expects($this->once())
            ->method('findUsersBalance')
            ->with($user)
            ->willReturn($userBalance)
        ;

        $this->userBalanceRepository->expects($this->never())
            ->method('save')
        ;

        $this->eventSubscriber->onUserRegisteredEvent($this->userRegisteredEvent);
    }

    /**
     * @test
     */
    public function shouldCreateUserBalanceWhenUserDoesNotHaveOne()
    {
        $user = $this->getUserInstance();

        $this->userRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with($this->userRegisteredEvent->getEmail())
            ->willReturn($user)
        ;

        $this->userBalanceRepository->expects($this->once())
            ->method('findUsersBalance')
            ->with($user)
            ->willReturn(null)
        ;

        $this->userBalanceRepository->expects($this->once())
            ->method('save')
        ;

        $this->eventSubscriber->onUserRegisteredEvent($this->userRegisteredEvent);
    }
}