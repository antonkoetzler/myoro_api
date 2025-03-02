<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    protected LoginRequest $request;
    protected array $rules;

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
        $this->assertEquals('', $validator->errors()->first('username'));
        $this->assertEquals('', $validator->errors()->first('email'));
        $this->assertEquals('The password field is required.', $validator->errors()->first('password'));
    }

    public function testInvalidTypesCase(): void
    {
        $validationData = [
            'username' => $this->faker->randomNumber(),
            'email' => $this->faker->randomNumber(),
            'password' => $this->faker->randomNumber(),
        ];
        $validator = Validator::make($validationData, $this->rules);
        $this->assertTrue($validator->fails());
        $this->assertEquals('The username field must be a string.', $validator->errors()->first('username'));
        $this->assertEquals('The email field must be a string.', $validator->errors()->first('email'));
        $this->assertEquals('The password field must be a string.', $validator->errors()->first('password'));
    }

    public function testInvalidEmailCase(): void
    {
        $validationData = [
            'email' => 'Hello, World!',
        ];
    }
}
