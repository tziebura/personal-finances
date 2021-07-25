<?php


namespace App\Tests\Unit\Service;


use App\Entity\User;
use App\Service\UserAuthenticationService;
use App\Service\UserPasswordEncoderInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserAuthenticationServiceTest extends TestCase
{
    private UserAuthenticationService $subject;

    public function setUp(): void
    {
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->expects($this->once())
            ->method('verify')
        ;

        $this->subject = new UserAuthenticationService($passwordEncoder);
    }

    /**
     * @test
     */
    public function shouldVerifyPassword()
    {
        $user = new User(
            Uuid::uuid4(),
            'test@example.com',
            'password'
        );

        $this->subject->checkCredentials($user, 'password');
    }
}