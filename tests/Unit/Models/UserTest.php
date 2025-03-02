<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testConstantsVariables(): void
    {
        $this->assertNotEmpty(User::NAME, 'name');
        $this->assertNotEmpty(User::USERNAME, 'username');
        $this->assertNotEmpty(User::EMAIL, 'email');
        $this->assertNotEmpty(User::PASSWORD, 'password');
    }

    public function testFillable(): void
    {
        $user = User::factory()->create();
        $this->assertTrue(in_array(User::NAME, $user->getFillable()));
        $this->assertTrue(in_array(User::USERNAME, $user->getFillable()));
        $this->assertTrue(in_array(User::EMAIL, $user->getFillable()));
        $this->assertTrue(in_array(User::PASSWORD, $user->getFillable()));
    }

    public function testHidden(): void
    {
        $user = User::factory()->create();
        $this->assertTrue(in_array(User::PASSWORD, $user->getHidden()));
        $this->assertTrue(in_array(User::REMEMBER_TOKEN, $user->getHidden()));
    }

    public function testCasts(): void
    {
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $method = $reflection->getMethod('casts');
        $method->setAccessible(true);
        $casts = $method->invoke($user);
        $this->assertEquals([
            User::EMAIL_VERIFIED_AT => 'datetime',
            User::PASSWORD => 'hashed',
        ], $casts);
    }

    public function testCreation(): void
    {
        $data = [
            User::NAME => $this->faker->name(),
            User::USERNAME => $this->faker->userName(),
            User::EMAIL => $this->faker->email(),
        ];
        User::factory()->create($data);
        $this->assertDatabaseHas('users', $data);
    }
}
