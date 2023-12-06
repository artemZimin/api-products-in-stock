<?php

namespace App\Exceptions;

class NotAvailableException extends CustomException
{
    public static function create($entityName): NotAvailableException
    {
        return new self(
            __('error.not_available', ['entity' => $entityName]),
            400
        );
    }
}
