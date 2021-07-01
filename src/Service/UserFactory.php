<?php


namespace App\Service;


use App\Entity\User;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

class UserFactory implements UserFactoryInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(UuidInterface $id, string $email, string $password): User
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('Invalid email provided: %s.', $email));
        }

        if (mb_strlen($password) < 6) {
            throw new InvalidArgumentException('Password should be at least 6 characters long');
        }

        return new User(
            $id,
            $email,
            $this->passwordEncoder->encode($password)
        );
    }
}