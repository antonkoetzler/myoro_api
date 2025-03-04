<?php

namespace App\Exceptions;

class UnimplementedException extends ApiException
{
    /** @var string */
    protected $message = 'Not implemented.';

    /** @var int */
    protected $code = 501;
}
