<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ApiException;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ApiExceptionTest extends TestCase
{
    public function testRender(): void
    {
        $message = 'Custom exception';
        $code = 400;
        $exception = new class ($message, $code) extends ApiException {};
        $response = $exception->render();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => $message], $response->getData(true));
    }
}
