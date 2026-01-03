<?php

namespace App\Exceptions;

class ForbiddenException extends ApiException
{
    public function __construct(string $message = 'Access forbidden')
    {
        parent::__construct($message, 403);
    }
}
