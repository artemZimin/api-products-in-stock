<?php

namespace App\Exceptions;

class NotFoundException extends CustomException
{
    public static function create(string $entityName): NotFoundException
    {
        return new self(
            __('error.not_found', ['entity' => $entityName]),
            404
        );
    }
}
