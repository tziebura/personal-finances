<?php


namespace App\Service;


use App\Entity\User;

interface UserAuthenticationServiceInterface
{
    public function checkCredentials(User $user, string $password): bool;
}