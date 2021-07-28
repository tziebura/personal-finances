<?php


namespace App\Tests\Unit\Service;


use App\Enum\UserRole;
use App\Service\UserRoleManager;
use App\Tests\Helpers\User;
use PHPUnit\Framework\TestCase;

class UserRoleManagerTest extends TestCase
{
    use User;

    private UserRoleManager $userRoleManager;

    public function setUp(): void
    {
        $this->userRoleManager = new UserRoleManager();
    }

    /**
     * @test
     */
    public function shouldAddUserRoleWhenTheRoleIsNotYetGranted()
    {
        $expected = [
            UserRole::ROLE_USER()->getValue(),
            UserRole::ROLE_ADMIN()->getValue(),
        ];

        $user = $this->getUserInstance();
        $this->userRoleManager->promote($user, UserRole::ROLE_ADMIN());

        $this->assertEquals($expected, $user->getRoles());
    }

    /**
     * @test
     */
    public function shouldNotAddUserRoleWhenTheRoleIsAlreadyGranted()
    {
        $expected = [
            UserRole::ROLE_USER()->getValue(),
            UserRole::ROLE_ADMIN()->getValue(),
        ];

        $user = $this->getUserInstance();
        $user->addRole(UserRole::ROLE_ADMIN());

        $this->userRoleManager->promote($user, UserRole::ROLE_ADMIN());

        $this->assertEquals($expected, $user->getRoles());
    }

    /**
     * @test
     */
    public function shouldRevokeUserRoleWhenTheRoleIsGranted()
    {
        $expectedPreDemote = [
            UserRole::ROLE_USER()->getValue(),
            UserRole::ROLE_ADMIN()->getValue(),
        ];

        $expectedPostDemote = [
            UserRole::ROLE_USER()->getValue(),
        ];

        $user = $this->getUserInstance();
        $user->addRole(UserRole::ROLE_ADMIN());

        $this->assertEquals($expectedPreDemote, $user->getRoles());

        $this->userRoleManager->demote($user, UserRole::ROLE_ADMIN());

        $this->assertEquals($expectedPostDemote, $user->getRoles());
    }
}