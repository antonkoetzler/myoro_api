<?php

namespace Tests\Unit\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UnimplementedException;
use App\Http\Controllers\UserController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Mockery\Expectation;
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
        return new class($this->token) {
            private string $token;

            public function __construct(string $token)
            {
                $this->token = $token;
            }

            /**
             * When Laravel tries to access ->plainTextToken, this magic method is called.
             *
             * This mimics how Laravel's actual token object works.
             *
             * @param string $name
             * @return string|null
             */
            public function __get(string $name): string|null
            {
                return $name === 'plainTextToken' ? $this->token : null;
            }
        };
    }

    private function createUserMock(): User | MockInterface
    {
        return $this->mock(User::class, function (MockInterface $mock) {
            /** @var Expectation */
            $getAttributeExpectation = $mock->shouldReceive('getAttribute');
            $getAttributeExpectation->with('id')->andReturn($this->user->id);

            /** @var Expectation */
            $createTokenExpectation = $mock->shouldReceive('createToken');
            $createTokenExpectation->with('MyoroAPI')->andReturn($this->personalTokenMock);
        });
    }

    private function createRequestMock(string $requestClass): MockInterface
    {
        return $this->mock($requestClass, function (MockInterface $mock) {
            /** @var Expectation */
            $allExpectation = $mock->shouldReceive('all');
            $allExpectation->andReturn([]);

            /** @var Expectation */
            $validatedExpectation = $mock->shouldReceive('validated');
            $validatedExpectation->andReturn([]);
        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
        $this->controller = new UserController($this->userService);
        /** @var User */
        $userFactory = User::factory()->count(1)->create()->first();
        $this->user = $userFactory;
        $this->token = $this->faker->text();
        $this->personalTokenMock = $this->createPersonalTokenMock();
        $this->userMock = $this->createUserMock();
    }

    public function testIndex(): void
    {
        $response = $this->controller->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $content = $response->getContent();
        $this->assertIsString($content, 'Response content is not a string.');
        /** @var array<string, string|int> */
        $responseData = json_decode($content, true);
        $this->assertEquals([$this->user->toArray()], $responseData);
    }

    public function testStore(): void
    {
        $this->expectException(UnimplementedException::class);
        $this->controller->store();
    }

    public function testShowException(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->controller->show('1');
    }

    public function testShowSuccessful(): void
    {
        $response = $this->controller->show($this->user->id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        /** @var object{id: int|string} */
        $data = $response->getData();
        $this->assertEquals($this->user->id, $data->id);
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
        /** @var SignupRequest&MockInterface */
        $requestMock = $this->createRequestMock(SignupRequest::class);

        /** @var UserService&MockInterface */
        $userServiceMock = $this->mock(UserService::class, function (MockInterface $mock) {
            /** @var Expectation */
            $expectation = $mock->shouldReceive('signup');
            $expectation->with([])->andThrow(new Exception());
        });

        $this->expectException(Exception::class);
        $controller = new UserController($userServiceMock);
        $controller->signup($requestMock);
    }

    public function testSignupSuccess(): void
    {
        /** @var SignupRequest&MockInterface */
        $requestMock = $this->createRequestMock(SignupRequest::class);

        /** @var UserService&MockInterface */
        $userServiceMock = $this->mock(UserService::class, function (MockInterface $mock) {
            /** @var Expectation */
            $expectation = $mock->shouldReceive('signup');
            $expectation->once()->with([])->andReturn($this->userMock);
        });

        $controller = new UserController($userServiceMock);
        $response = $controller->signup($requestMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $content = $response->getContent();
        $this->assertIsString($content, 'Response content is not a string.');

        /** @var array<string, string|int> */
        $responseData = json_decode($content, true);
        $responseDataMessage = (string) $responseData['message'];
        $responseDataUserId = (int) $responseData['user_id'];
        $responseDataToken = (string) $responseData['token'];

        $this->assertArrayHasKey('message', $responseData);
        $this->assertArrayHasKey('user_id', $responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertEquals('User registered successfully!', $responseDataMessage);
        $this->assertEquals($this->user->id, $responseDataUserId);
        $this->assertEquals($this->token, $responseDataToken);
    }

    public function testLoginException(): void
    {
        /** @var LoginRequest&MockInterface */
        $requestMock = $this->createRequestMock(LoginRequest::class);

        /** @var UserService&MockInterface */
        $userServiceMock = $this->mock(UserService::class, function (MockInterface $mock) {
            /** @var Expectation */
            $expectation = $mock->shouldReceive('login');
            $expectation->with([])->andThrow(new Exception());
        });

        $this->expectException(Exception::class);
        $controller = new UserController($userServiceMock);
        $controller->login($requestMock);
    }

    public function testLoginSuccess(): void
    {
        /** @var LoginRequest&MockInterface */
        $request = $this->mock(LoginRequest::class, function (MockInterface $mock) {
            /** @var Expectation */
            $allExpectation = $mock->shouldReceive('all');
            $allExpectation->andReturn([]);

            /** @var Expectation */
            $validatedExpectation = $mock->shouldReceive('validated');
            $validatedExpectation->andReturn([]);
        });

        /** @var UserService&MockInterface */
        $userServiceMock = $this->mock(UserService::class, function (MockInterface $mock) {
            /** @var Expectation */
            $expectation = $mock->shouldReceive('login');
            $expectation->with([])->andReturn($this->userMock);
        });

        $controller = new UserController($userServiceMock);
        $response = $controller->login($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $content = $response->getContent();
        $this->assertIsString($content, 'Response content is not a string.');

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
