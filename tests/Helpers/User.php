<?php


namespace App\Tests\Helpers;


use Ramsey\Uuid\Uuid;

trait User
{
    public function getUserInstance(): \App\Entity\User
    {
        return new \App\Entity\User(
            Uuid::uuid4(),
            'test@example.com',
            '12345678'
        );
    }
}