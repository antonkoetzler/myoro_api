<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SignupRequestTest extends TestCase
{
    protected SignupRequest $request;
    /** @var array<string, string> */
    protected array $rules;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new SignupRequest();
        $this->rules = $this->request->rules();
    }

    public function testAuthorize(): void
    {
        $this->assertTrue($this->request->authorize());
    }

    public function testEmptyCase(): void
    {
        $validationData = [];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The ' . User::NAME . ' field is required.', $validator->errors()->first(User::NAME));
        $this->assertEquals('The ' . User::USERNAME . ' field is required.', $validator->errors()->first(User::USERNAME));
        $this->assertEquals('The ' . User::EMAIL . ' field is required.', $validator->errors()->first(User::EMAIL));
        $this->assertEquals('The ' . User::PASSWORD . ' field is required.', $validator->errors()->first(User::PASSWORD));
    }

    public function testInvalidTypesCase(): void
    {
        $validationData = [
            User::NAME => $this->faker->randomNumber(),
            User::USERNAME => $this->faker->randomNumber(),
            User::EMAIL => $this->faker->randomNumber(),
            User::PASSWORD => $this->faker->randomNumber(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The ' . User::NAME . ' field must be a string.', $validator->errors()->first(User::NAME));
        $this->assertEquals('The ' . User::USERNAME . ' field must be a string.', $validator->errors()->first(User::USERNAME));
        $this->assertEquals('The ' . User::EMAIL . ' field must be a string.', $validator->errors()->first(User::EMAIL));
        $this->assertEquals('The ' . User::PASSWORD . ' field must be a string.', $validator->errors()->first(User::PASSWORD));
    }

    public function testInvalidEmailCase(): void
    {
        $validationData = [
            User::EMAIL => $this->faker->word(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The ' . User::EMAIL . ' field must be a valid email address.', $validator->errors()->first(User::EMAIL));
    }

    public function testSuccessCase(): void
    {
        $password = $this->faker->lexify(str_repeat('?', $this->faker->numberBetween(8, 255)));
        $validationData = [
            User::NAME => $this->faker->name(),
            User::USERNAME => $this->faker->lexify(str_repeat('?', $this->faker->numberBetween(8, 255))),
            User::EMAIL => $this->faker->email(),
            User::PASSWORD => $password,
            User::PASSWORD_CONFIRMATION => $password,
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->passes());
    }
}
