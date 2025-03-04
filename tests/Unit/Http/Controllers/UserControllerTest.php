<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UnimplementedException;
use App\Http\Controllers\UserController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use ArrayAccess;
use AssertionError;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected UserService $userService;
    protected UserController $controller;
    protected User $user;
    protected string $token;
    protected object $personalTokenMock;
    protected User|MockInterface $userMock;

    private function createPersonalTokenMock(): object
    {
        return new class ($this->token) {
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
                return $name === 'plainTextToken' ? $this->token : null;
            }
        };
    }

    private function createUserMock(): User | MockInterface
    {
        return $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('id')->andReturn($this->user->id);
            $mock->shouldReceive('createToken')->with('MyoroAPI')->andReturn($this->personalTokenMock);
        });
    }

    private function createRequestMock(string $requestClass): MockInterface
    {
        return $this->mock($requestClass, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
        $this->controller = new UserController($this->userService);
        $this->user = User::factory()->create();
        $this->token = $this->faker->text();
        $this->personalTokenMock = $this->createPersonalTokenMock();
        $this->userMock = $this->createUserMock();
    }

    public function testIndex(): void
    {
        $response = $this->controller->index(new Request());
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals([$this->user->toArray()], json_decode($response->getContent(), true));
    }

    public function testStore(): void
    {
        $this->expectException(UnimplementedException::class);
        $this->controller->store();
    }

    public function testShowException(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->controller->show(1);
    }

    public function testShowSuccessful(): void
    {
        $response = $this->controller->show($this->user->id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($this->user->id, $response->getData()->id);
    }

    public function testUpdate(): void
    {
        $this->expectException(UnimplementedException::class);
        $this->controller->update();
    }

    public function testDestroy(): void
    {
        $this->expectException(UnimplementedException::class);
        $this->controller->destroy();
    }

    public function testSignupException(): void
    {
        /** @var SignupRequest|LegacyMockInterface|MockInterface */
        $requestMock = $this->createRequestMock(SignupRequest::class);

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('signup')->with([])->andThrow(new AssertionError());
        });

        $this->expectException(AssertionError::class);
        $controller = new UserController($userServiceMock);
        $controller->signup($requestMock);
    }

    public function testSignupSuccess(): void
    {
        /** @var SignupRequest|MockInterface */
        $requestMock = $this->createRequestMock(SignupRequest::class);

        /** @var UserService|MockInterface */
        $userServiceMock = $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('signup')->once()->with([])->andReturn($this->userMock);
        });

        $controller = new UserController($userServiceMock);
        $response = $controller->signup($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals('User registered successfully!', $responseData['message']);
        $this->assertEquals($this->user->id, $responseData['user_id']);
        $this->assertEquals($this->token, $responseData['token']);
    }

    public function testLoginException(): void
    {
        /** @var LoginRequest|MockInterface */
        $requestMock = $this->createRequestMock(LoginRequest::class);

        /** @var UserService|MockInterface */
        $userServiceMock = $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('login')->with([])->andThrow(new AssertionError());
        });

        $this->expectException(AssertionError::class);
        $controller = new UserController($userServiceMock);
        $controller->login($requestMock);
    }

    public function testLoginSuccess(): void
    {
        /** @var LoginRequest|LegacyMockInterface|MockInterface */
        $request = $this->mock(LoginRequest::class, function ($mock) {
            $mock->shouldReceive('all')->andReturn([]);
            $mock->shouldReceive('validated')->andReturn([]);
        });

        /** @var UserService|LegacyMockInterface|MockInterface */
        $userServiceMock = $this->mock(UserService::class, function ($mock) {
            $mock->shouldReceive('login')->with([])->andReturn($this->userMock);
        });

        $controller = new UserController($userServiceMock);
        $response = $controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $content = $response->getContent();
        $this->assertIsString($content, 'Response content is not a string');
        /** @var array<string, string|int> */
        $responseData = json_decode($content, true);
        $responseDataMessage = (string) $responseData['message'];
        $responseDataUserId = (int) $responseData['user_id'];
        $responseDataToken = (string) $responseData['token'];

        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals('Login successful!', $responseDataMessage);
        $this->assertEquals($this->user->id, $responseDataUserId);
        $this->assertEquals($this->token, $responseDataToken);
    }
}
