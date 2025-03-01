<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\UnimplementedException;
use Tests\TestCase;

class UnimplementedExceptionTest extends TestCase
{
    function testException(): void
    {
        $exception = new UnimplementedException();
        $this->assertEquals('Not implemented.', $exception->getMessage());
        $this->assertEquals(501, $exception->getCode());
        $this->expectException(UnimplementedException::class);
        throw $exception;
    }
}
