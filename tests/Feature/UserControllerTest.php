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
}
