<?php


namespace App\Service;


class ComplexUserPasswordEncoder implements UserPasswordEncoderInterface
{
    private int $cost;
    private string $passPhrase;

    public function __construct(int $cost, string $passPhrase)
    {
        $this->cost = $cost;
        $this->passPhrase = $passPhrase;
    }

    public function encode(string $plainPassword): string
    {
        $plainPassword = $this->passPhrase . $plainPassword;
        $plainPassword = base64_encode($plainPassword);

        return password_hash($plainPassword, PASSWORD_ARGON2I, [
            'cost' => $this->cost
        ]);
    }

    public function verify(string $password, string $hash): bool
    {
        $password = $this->passPhrase . $password;
        $password = base64_encode($password);

        return password_verify($password, $hash);
    }
}