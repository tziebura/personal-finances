<?php


namespace App\Service;


class ComplexUserPasswordEncoder implements UserPasswordEncoderInterface
{
    private int $cost;
    private string $passPhrase;
    private string $salt;

    public function __construct(int $cost, string $passPhrase)
    {
        $this->cost = $cost;
        $this->passPhrase = $passPhrase;
        $this->salt = '';
    }

    public function setSalt(string $salt)
    {
        $this->salt = $salt;
    }

    public function encode(string $plainPassword): string
    {
        $plainPassword = $this->passPhrase . $plainPassword;
        $plainPassword = base64_encode($plainPassword);

        $options = [
            'cost' => $this->cost
        ];

        if ($this->salt) {
            $options['salt'] = $this->salt;
        }

        return password_hash($plainPassword, PASSWORD_BCRYPT, $options);
    }

    public function verify(string $password, string $hash): bool
    {
        $password = $this->passPhrase . $password;
        $password = base64_encode($password);

        return password_verify($password, $hash);
    }
}