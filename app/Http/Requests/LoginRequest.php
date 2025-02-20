<?php

namespace App\Http\Requests;

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
            'username' => 'nullable|string',
            'email' => 'nullable|string|email',
            'password' => 'required|string',
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
            if (!$this->username && !$this->email) {
                $validator->errors()->add('username', 'Username or email is required.');
            }
            if ($this->username && $this->email) {
                $validator->errors()->add('username', 'Only one of username or email should be provided.');
            }
        });
    }
}
