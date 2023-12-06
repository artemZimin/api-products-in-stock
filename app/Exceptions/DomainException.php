<?php

namespace App\Exceptions;

class DomainException extends CustomException
{
    public static function create(string $message, int $code): DomainException
    {
        return new self($message, $code);
    }
}
