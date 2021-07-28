<?php


namespace App\Entity;


use App\Enum\UserRole;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    use Timestamps;
    use Toggleable;

    private UuidInterface $id;
    private string $email;
    private string $password;
    private array $roles;

    public function __construct(UuidInterface $id, string $email, string $password)
    {
        $this->id       = $id;
        $this->email    = $email;
        $this->password = $password;

        $this->roles = [
            UserRole::ROLE_USER()->getValue(),
        ];

        $this->createdAt = new DateTimeImmutable();
        $this->enabled   = false;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return array_unique([...$this->roles, UserRole::ROLE_USER()->getValue()]);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials() {}

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function addRole(UserRole $role): void
    {
        $roles = $this->roles;
        $roles[] = $role->getValue();
        $this->roles = array_unique($roles);
    }

    public function removeRole(UserRole $role): void
    {
        $roleIndex = array_search($role->getValue(), $this->roles);

        if ($roleIndex === false) {
            return;
        }

        unset($this->roles[$roleIndex]);
        $this->roles = array_values($this->roles);
    }
}