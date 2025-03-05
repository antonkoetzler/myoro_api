<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testIndex(): void
    {
        $userQuantity = $this->faker->numberBetween(0, 10);
        User::factory()->count($userQuantity)->create();
        $response = $this->getJson('/api/users');
        $response->assertStatus(200)->assertJsonCount($userQuantity);
    }

    public function testStore(): void
    {
        $data = [
            User::NAME => $this->faker->name(),
            User::EMAIL => $this->faker->email(),
            User::PASSWORD => $this->faker->password(),
        ];
        $response = $this->postJson('/api/users', $data);
        $response->assertStatus(501);
    }

    public function testShow(): void
    {
        /** @var User */
        $user = User::factory()->create();
        $response = $this->getJson('/api/users/' . $user->id);
        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                User::NAME => $user->name,
                User::EMAIL => $user->email,
            ]);
    }

    public function testUpdate(): void
    {
        /** @var User */
        $user = User::factory()->create();
        $data = [];
        $response = $this->putJson('/api/users/' . $user->id, $data);
        $response->assertStatus(501);
    }

    public function testDestroy(): void
    {
        /** @var User */
        $user = User::factory()->create();
        $response = $this->deleteJson('/api/users/' . $user->id);
        $response->assertStatus(501);
    }

    public function testSignupException(): void
    {
        $data = [];
        $response = $this->postJson('/api/users/signup', $data);
        $response->assertStatus(422)->assertJson([
            'message' => 'The ' . User::NAME . ' field is required. (and 3 more errors)',
        ]);
    }

    public function testSignupSuccess(): void
    {
        $data = $this->makeValidUserSignupRequestData();
        $response = $this->postJson('api/users/signup', $data);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user_id',
                'token',
            ])->assertJson([
                'message' => 'User registered successfully!',
            ]);
    }

    public function testLoginException(): void
    {
        $data = [];
        $response = $this->postJson('/api/users/login', $data);
        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The ' . User::PASSWORD . ' field is required. (and 2 more errors)',
            ]);
    }

    public function testLoginSuccess(): void
    {
        $data = $this->makeValidUserSignupRequestData();
        $signupResponse = $this->postJson('api/users/signup', $data);
        $signupResponse->assertStatus(201);

        $emailProvided = $this->faker->boolean();
        $username = $emailProvided ? null : $data[User::USERNAME];
        $email = $emailProvided ? $data[User::EMAIL] : null;
        $password = $data[User::PASSWORD];
        $loginResponse = $this->postJson(
            '/api/users/login',
            [
                User::USERNAME => $username,
                User::EMAIL => $email,
                User::PASSWORD => $password,
            ],
        );
        $loginResponse
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user_id',
                'token',
            ])
            ->assertJson([
                'message' => 'Login successful!',
            ]);
    }

    /**
     * Helper function to make the request JSON to signup a User
     *
     * @see User
     * @return array<string|string>
     */
    private function makeValidUserSignupRequestData(): array
    {
        $name = $this->faker->name();
        $username = $this->faker->lexify(str_repeat('?', $this->faker->numberBetween(8, 255)));
        $email = $this->faker->email();
        $password = $this->faker->lexify(str_repeat('?', $this->faker->numberBetween(8, 255)));
        return [
            User::NAME => $name,
            User::USERNAME => $username,
            User::EMAIL => $email,
            User::PASSWORD => $password,
            User::PASSWORD_CONFIRMATION => $password,
        ];
    }
}
