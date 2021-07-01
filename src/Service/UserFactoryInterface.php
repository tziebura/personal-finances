<?php


namespace App\Service;


use App\Entity\User;
use Ramsey\Uuid\UuidInterface;

interface UserFactoryInterface
{
    public function createUser(UuidInterface $id, string $email, string $password): User;
}