<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception to use when a certain resouce was not found.
 */
class ResourceNotFoundException extends Exception
{
    protected $message = 'Resource not found.';
    protected $code = 404;
}
