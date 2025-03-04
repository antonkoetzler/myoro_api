<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * Service class of User.
 */
class UserService
{
    /**
     * IOC-able User:all.
     *
     * @return Collection<int, User>
     */
    public function all(): Collection
    {
        return User::all();
    }

    /**
     * Signs up a User.
     *
     * @param array<string, string|null> $validatedData
     *
     * @return User
     */
    public function signup(array $validatedData): User
    {
        $password = (string) $validatedData[User::PASSWORD];

        $user = User::create([
            User::NAME => $validatedData[User::NAME],
            User::USERNAME => $validatedData[User::USERNAME],
            User::EMAIL => $validatedData[User::EMAIL],
            User::PASSWORD => bcrypt($password),
        ]);

        return $user;
    }

    /**
     * Logs in a user.
     *
     * @param array<string, string> $validatedData
     *
     * @return User
     */
    public function login(array $validatedData): User
    {
        /** @var User|null */
        $user = User::where(User::USERNAME, $validatedData[User::USERNAME])
            ->orWhere(User::EMAIL, $validatedData[User::EMAIL])
            ->first();

        if (!$user || !Hash::check($validatedData[User::PASSWORD], $user->password)) {
            throw new \Exception(
                'Username/email and/or password are incorrect.',
                401,
            );
        }

        return $user;
    }
}
