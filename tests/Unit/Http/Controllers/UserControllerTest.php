<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UnimplementedException;
use App\Http\Controllers\UserController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use AssertionError;
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
        $controller = new UserController(new UserService());

        $response = $controller->index(new Request());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals([$user->toArray()], json_decode($response->getContent(), true));
    }

    public function testStore(): void
    {
        $this->expectException(UnimplementedException::class);
        $controller = new UserController(new UserService());
        $controller->store();
    }

    public function testShowException(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $controller = new UserController(new UserService());
        $controller->show(1);
    }

    public function testShowSuccessful(): void
    {
        $user = User::factory()->create();
        $controller = new UserController(new UserService());

        $response = $controller->show($user->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($user->id, $response->getData()->id);
    }

    public function testUpdate(): void
    {
        $this->expectException(UnimplementedException::class);
        $controller = new UserController(new UserService());
        $controller->update();
    }

    public function testDestroy(): void
    {
        $this->expectException(UnimplementedException::class);
        $controller = new UserController(new UserService());
        $controller->destroy();
    }

    public function testSignupException(): void
    {
        /** @var SignupRequest|LegacyMockInterface|MockInterface */
        $request = Mockery::mock(SignupRequest::class, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        })->makePartial();

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('signup')
            ->with([])
            ->andThrow(new AssertionError());

        $this->expectException(AssertionError::class);
        $controller = new UserController($userServiceMock);
        $controller->signup($request);
    }

    public function testSignupSuccess(): void
    {
        // Mock variables for $userMock.
        $token = $this->faker()->text();
        $userId = $this->faker()->randomDigit();

        // Create a simple class to mimic Laravel's personal access token.
        //
        // This is a "anonymous class" - a class without a name, created on the spot.
        $personalTokenMock = new class($token) {
            private $token;
            public function __construct($token)
            {
                $this->token = $token;
            }
            // When Laravel tries to access ->plainTextToken, this magic method is called.
            //
            // This mimics how Laravel's actual token object works
            public function __get($name)
            {
                if ($name === 'plainTextToken') {
                    return $this->token;
                }
            }
        };

        /** @var User|LegacyMockInterface|MockInterface */
        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($userId);
        // When the code calls $user->createToken('MyoroAPI'),
        // return our fake token object that we created above
        $userMock->shouldReceive('createToken')
            ->with('MyoroAPI')
            ->once()
            ->andReturn($personalTokenMock);

        /** @var SignupRequest|LegacyMockInterface|MockInterface */
        $request = Mockery::mock(SignupRequest::class, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        })->makePartial();  // makePartial() allows other methods to work normally

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('signup')
            ->with([])
            ->andReturn($userMock);

        $controller = new UserController($userServiceMock);
        $response = $controller->signup($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals('User registered successfully!', $responseData['message']);
        $this->assertEquals($userId, $responseData['user_id']);
        $this->assertEquals($token, $responseData['token']);
    }

    public function testLoginException(): void
    {
        /** @var LoginRequest|LegacyMockInterface|MockInterface */
        $request = Mockery::mock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        })->makePartial();

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('login')
            ->with([])
            ->andThrow(new AssertionError());

        $this->expectException(AssertionError::class);
        $controller = new UserController($userServiceMock);
        $controller->login($request);
    }

    public function testLoginSuccess(): void
    {
        // Mock variables for $userMock.
        $token = $this->faker()->text();
        $userId = $this->faker()->randomDigit();

        // Create a simple class to mimic Laravel's personal access token.
        //
        // This is a "anonymous class" - a class without a name, created on the spot.
        $personalTokenMock = new class($token) {
            private $token;
            public function __construct($token)
            {
                $this->token = $token;
            }
            // When Laravel tries to access ->plainTextToken, this magic method is called.
            //
            // This mimics how Laravel's actual token object works
            public function __get($name)
            {
                if ($name === 'plainTextToken') {
                    return $this->token;
                }
            }
        };

        /** @var User|LegacyMockInterface|MockInterface */
        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($userId);
        // When the code calls $user->createToken('MyoroAPI'),
        // return our fake token object that we created above
        $userMock->shouldReceive('createToken')
            ->with('MyoroAPI')
            ->once()
            ->andReturn($personalTokenMock);

        /** @var LoginRequest|LegacyMockInterface|MockInterface */
        $request = Mockery::mock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        })->makePartial();  // makePartial() allows other methods to work normally

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = Mockery::mock(UserService::class);
        $userServiceMock->shouldReceive('login')
            ->with([])
            ->andReturn($userMock);

        $controller = new UserController($userServiceMock);
        $response = $controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals('Login successful!', $responseData['message']);
        $this->assertEquals($userId, $responseData['user_id']);
        $this->assertEquals($token, $responseData['token']);
    }
}
