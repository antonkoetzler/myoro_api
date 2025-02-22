<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\UnimplementedException;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testIndex(): void
    {
        $user = User::factory()->create();

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('all')
            ->once()
            ->andReturn(collect([$user]));

        $controller = new UserController($userServiceMock);
        $response = $controller->index(new Request());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(json_encode([$user->toArray()]), $response->getContent());
    }

    public function testStore(): void
    {
        $this->expectException(UnimplementedException::class);
        $controller = new UserController(new UserService());
        $controller->store();
    }

    // TODO: Still have to finish.
    public function testShowWhenUserExists(): void
    {
        /* $user = User::factory()->create(); */

        $controller = new UserController(new UserService());
        $response = $controller->show(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
