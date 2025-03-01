<?php

namespace App\Exceptions;

use Exception;

class UnimplementedException extends Exception
{
    protected $message = 'Not implemented.';
    protected $code = 501;
}
