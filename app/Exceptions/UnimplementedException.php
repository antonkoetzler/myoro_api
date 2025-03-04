<?php

namespace App\Exceptions;

class UnimplementedException extends ApiException
{
    /** @var string */
    protected $message = 'This feature is not implemented yet.';

    /** @var int */
    protected $code = 501;
}
