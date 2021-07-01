<?php


namespace App\Enum;


use MyCLabs\Enum\Enum;

/**
 * @method static ROLE_USER
 * @method static ROLE_ADMIN
 * @method static ROLE_SUPER_ADMIN
 */
class UserRole extends Enum
{
    private const ROLE_USER        = 'ROLE_USER';
    private const ROLE_ADMIN       = 'ROLE_ADMIN';
    private const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
}