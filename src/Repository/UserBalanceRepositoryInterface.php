<?php


namespace App\Repository;


use App\Entity\User;
use App\Entity\UserBalance;

interface UserBalanceRepositoryInterface
{
    public function findUsersBalance(User $user): ?UserBalance;
    public function save(UserBalance $balance): void;
}