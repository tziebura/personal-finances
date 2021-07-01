<?php


namespace App\Entity;


use App\Enum\UserRole;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class User
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
        return $this->roles;
    }
}