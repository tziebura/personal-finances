<?php


namespace App\Service;


interface UserPasswordEncoderInterface
{
    public function encode(string $plainPassword): string;
    public function verify(string $password, string $hash): bool;
}