<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Exception;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = new UserService();
    }

    public function testAll(): void
    {
        $userQuantity = $this->faker->numberBetween(0, 10);
        User::factory()->count($userQuantity)->create();
        $result = $this->userService->all();
        $this->assertCount($userQuantity, $result);
    }

    public function testSignup(): void
    {
        $validatedData = [
            User::NAME => $this->faker->name(),
            User::USERNAME => $this->faker->userName(),
            User::EMAIL => $this->faker->email(),
            User::PASSWORD => $this->faker->password(),
        ];
        $user = $this->userService->signup($validatedData);
        $this->assertDatabaseHas('users', [
            User::NAME => $validatedData[User::NAME],
            User::USERNAME => $validatedData[User::USERNAME],
            User::EMAIL => $validatedData[User::EMAIL],
        ]);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testLoginException(): void
    {
        User::factory()->create([
            User::PASSWORD => bcrypt('Hello, World!'),
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Username/email and/or password are incorrect.');
        $this->expectExceptionCode(401);

        $this->userService->login([
            User::USERNAME => $this->faker->userName(),
            User::EMAIL => $this->faker->email(),
            User::PASSWORD => 'Wrong password.',
        ]);
    }

    public function testLoginSuccess(): void
    {
        $password = $this->faker->password();
        /** @var User */
        $user = User::factory()->create([
            User::PASSWORD => bcrypt($password),
        ]);
        $validatedData = [
            User::USERNAME => $user->username,
            User::EMAIL => $user->email,
            User::PASSWORD => $password,
        ];
        $result = $this->userService->login($validatedData);
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }
}
