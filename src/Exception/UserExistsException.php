<?php


namespace App\Exception;


use RuntimeException;
use Throwable;

class UserExistsException extends RuntimeException
{
    public function __construct(string $login, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('User with login %s already exists.', $login);
        parent::__construct($message, $code, $previous);
    }
}