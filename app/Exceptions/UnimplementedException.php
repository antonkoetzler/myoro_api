<?php

namespace App\Exceptions;

class UnimplementedException extends ApiException
{
    protected $message = 'Not implemented.';
    protected $code = 501;
}

