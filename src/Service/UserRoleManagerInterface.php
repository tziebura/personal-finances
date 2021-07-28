<?php


namespace App\Service;


use App\Entity\User;
use App\Enum\UserRole;

interface UserRoleManagerInterface
{
    public function promote(User $user, UserRole $role): void;
    public function demote(User $user, UserRole $role): void;
}