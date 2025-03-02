<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Abstract exception class.
 */
abstract class ApiException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'error' => $this->getMessage(),
        ], $this->getCode());
    }
}
