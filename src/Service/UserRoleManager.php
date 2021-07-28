<?php


namespace App\Service;


use App\Entity\User;
use App\Enum\UserRole;

class UserRoleManager implements UserRoleManagerInterface
{

    public function promote(User $user, UserRole $role): void
    {
        $user->addRole($role);
    }

    public function demote(User $user, UserRole $role): void
    {
        $user->removeRole($role);
    }
}