<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    // TODO: No mockery, create a docker container for tests.
    public function testIndex(): void
    {
        // Arrange: Mock the User model's 'all' method
        $mock = Mockery::mock('alias:' . User::class);
        $mock->shouldReceive('all')
            ->once()
            ->andReturn(collect([
                (object) ['name' => 'John Doe'],
                (object) ['name' => 'Jane Doe'],
            ]));

        // Act: Call the controller method
        $controller = new UserController($this->createMock(UserService::class));
        $response = $controller->index(new Request());

        // Assert: Verify the response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('[{"name":"John Doe"},{"name":"Jane Doe"}]', $response->getContent());

        // Clean up Mockery after the test
        Mockery::close();
    }
}
