<?php

namespace App\Exceptions;

/**
 * Exception to use when a certain resouce was not found.
 */
class ResourceNotFoundException extends ApiException
{
    protected $message = 'Resource not found.';
    protected $code = 404;
}
