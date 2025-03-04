<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            User::NAME => 'required|string|max:255',
            User::USERNAME => 'required|string|min:8|max:255|unique:users,username',
            User::EMAIL => 'required|string|email|max:255|unique:users,email',
            User::PASSWORD => 'required|string|min:8|confirmed',
        ];
    }
}
