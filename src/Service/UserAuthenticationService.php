<?php


namespace App\Service;


use App\Entity\User;

class UserAuthenticationService implements UserAuthenticationServiceInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function checkCredentials(User $user, string $password): bool
    {
        return $this->passwordEncoder->verify($password, $user->getPassword());
    }
}