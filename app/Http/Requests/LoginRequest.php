<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Permits if the caller may make the request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules of the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            User::USERNAME => 'nullable|string',
            User::EMAIL => 'nullable|string|email',
            User::PASSWORD => 'required|string',
        ];
    }

    /**
     * Additional validation of the request
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ((!$this->username && !$this->email) || ($this->username && $this->email)) {
                $validator->errors()->add(User::USERNAME, 'Username (x)or email is required.');
                $validator->errors()->add(User::EMAIL, 'Username (x)or email is required.');
            }
        });
    }
}
