<?php


namespace App\Tests\Unit\Service;


use App\Entity\User;
use App\Service\UserFactory;
use App\Service\UserFactoryInterface;
use App\Service\UserPasswordEncoderInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserFactoryTest extends TestCase
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserFactoryInterface $userFactory;

    public function setUp(): void
    {
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->userFactory = new UserFactory(
            $this->passwordEncoder
        );
    }

    /**
     * @test
     */
    public function shouldCreateUserWhenDataIsValid()
    {
        $id       = Uuid::uuid4();
        $email    = 'test@example.com';
        $password = 'password';

        $this->passwordEncoder->method('encode')
            ->willReturn($password)
        ;

        $user = $this->userFactory->createUser(
            $id,
            $email,
            $password
        );

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($password, $user->getPassword());
    }

    /**
     * @test
     */
    public function shouldNotCreateUserWhenEmailIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->userFactory->createUser(Uuid::uuid4(), 'email', 'password');
    }

    /**
     * @test
     */
    public function shouldNotCreateUserWhenPasswordIsLessThanSixSignsLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->userFactory->createUser(Uuid::uuid4(), 'email@example.com', 'passw');
    }

    /**
     * @test
     */
    public function shouldNotCreateUserWhenEmailIsInvalidAndPasswordIsLessThanSixSingsLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->userFactory->createUser(Uuid::uuid4(), 'email', 'passw');
    }
}