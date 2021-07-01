<?php


namespace App\Service;


use App\DTO\UserRegistrationRequest;

interface UserRegistrationServiceInterface
{
    public function registerUser(UserRegistrationRequest $request): void;
}