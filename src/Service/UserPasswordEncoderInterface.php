<?php


namespace App\Service;


interface UserPasswordEncoderInterface
{
    public function encode(string $plainPassword): string;
}