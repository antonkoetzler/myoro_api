<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    protected LoginRequest $request;
    /** @var array<string, string|null> */
    protected array $rules;

    public function testAuthorize(): void
    {
        $this->assertTrue($this->request->authorize());
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new LoginRequest();
        $this->rules = $this->request->rules();
    }

    public function testEmptyCase(): void
    {
        $validationData = [];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('', $validator->errors()->first(User::USERNAME));
        $this->assertEquals('', $validator->errors()->first(User::EMAIL));
        $this->assertEquals('The ' . User::PASSWORD . ' field is required.', $validator->errors()->first(User::PASSWORD));
    }

    public function testInvalidTypesCase(): void
    {
        $validationData = [
            User::USERNAME => $this->faker->randomNumber(),
            User::EMAIL => $this->faker->randomNumber(),
            User::PASSWORD => $this->faker->randomNumber(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The ' . User::USERNAME . ' field must be a string.', $validator->errors()->first(User::USERNAME));
        $this->assertEquals('The ' . User::EMAIL . ' field must be a string.', $validator->errors()->first(User::EMAIL));
        $this->assertEquals('The ' . User::PASSWORD . ' field must be a string.', $validator->errors()->first(User::PASSWORD));
    }

    public function testInvalidEmailCase(): void
    {
        $validationData = [
            User::EMAIL => 'Hello, World!',
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The ' . User::EMAIL . ' field must be a valid email address.', $validator->errors()->first(User::EMAIL));
    }

    public function testNoUsernameAndEmailProvidedCase(): void
    {
        $validationData = [];
        $validator = Validator::make($validationData, $this->rules);
        $this->request->withValidator($validator);
        $this->assertTrue($validator->fails());
        $this->assertEquals('Username (x)or email is required.', $validator->errors()->first(User::USERNAME));
    }

    public function testUsernameAndEmailProvidedCase(): void
    {
        $validationData = [
            User::USERNAME => $this->faker->userName(),
            User::EMAIL => $this->faker->email(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->request->withValidator($validator);
        $this->assertTrue($validator->fails());
        $this->assertEquals('Username (x)or email is required.', $validator->errors()->first(User::USERNAME));
    }

    public function testSuccessCase(): void
    {
        $usernameProvided = $this->faker->boolean();
        $validationData = [
            User::USERNAME => $usernameProvided ? $this->faker->userName() : null,
            User::EMAIL => $usernameProvided ? null : $this->faker->email(),
            User::PASSWORD => $this->faker->password(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->passes());
    }
}
