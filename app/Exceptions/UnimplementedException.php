<?php

namespace App\Exceptions;

use Exception;

class UnimplementedException extends Exception
{
    protected $message = 'Not unimplemented.';
    protected $code = 501;
}
