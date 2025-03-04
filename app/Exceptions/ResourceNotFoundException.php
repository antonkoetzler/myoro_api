<?php

namespace App\Exceptions;

/**
 * Exception to use when a certain resouce was not found.
 */
class ResourceNotFoundException extends ApiException
{
    /** @var string */
    protected $message = 'Resource not found.';

    /** @var int */
    protected $code = 404;
}
