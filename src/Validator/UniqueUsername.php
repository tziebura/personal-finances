<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

class UniqueUsername extends Constraint
{
    public string $message = 'User "{{ string }}" already_exists';
}