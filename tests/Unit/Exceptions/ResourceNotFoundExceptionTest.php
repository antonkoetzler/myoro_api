<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ResourceNotFoundException;
use Tests\TestCase;

class ResourceNotFoundExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new ResourceNotFoundException();
        $this->assertEquals('Resource not found.', $exception->getMessage());
        $this->assertEquals(404, $exception->getCode());
        $this->expectException(ResourceNotFoundException::class);
        throw $exception;
    }
}

