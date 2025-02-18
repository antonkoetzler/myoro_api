<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Service class of User.
 */
class UserService
{
    /**
     * Signs up a User.
     *
     * @param array $validatedData
     * @return User
     */
    public function signup(array $validatedData): User
    {
        $user = User::create([
            User::NAME => $validatedData[User::NAME],
            User::USERNAME => $validatedData[User::USERNAME],
            User::EMAIL => $validatedData[User::EMAIL],
            User::PASSWORD => bcrypt($validatedData[User::PASSWORD]),
        ]);

        return $user;
    }

    /**
     * Logs in a user.
     *
     * @param array $validatedData
     * @return User
     */
    public function login(array $validatedData): User
    {
        $user = User::where(User::USERNAME, $validatedData[User::USERNAME])
            ->orWhere(User::EMAIL, $validatedData[User::EMAIL])
            ->first();

        if (!$user || !Hash::check($validatedData[User::PASSWORD], $user->password)) {
            throw new \Exception('Username/email and/or password are incorrect.', 401);
        }

        return $user;
    }
}

