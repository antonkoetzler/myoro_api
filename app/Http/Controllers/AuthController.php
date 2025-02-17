<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            User::NAME => ['required', 'string', 'max:255', 'unique:users,username'],
            User::USERNAME => ['required', 'string', 'max:255', 'unique:users,username'],
            User::EMAIL => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            User::PASSWORD => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create the user if they don't already exist in the DB.
        $user = User::create([
            User::NAME => $validated[User::NAME],
            User::USERNAME => $validated[User::USERNAME],
            User::EMAIL => $validated[User::EMAIL],
            User::PASSWORD => bcrypt($validated[User::PASSWORD]),
        ]);

        // Return success message + newly created user's ID.
        return response()->json([
            'message' => 'User registered successfully!',
            'user_id'    => $user->id,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            User::USERNAME => ['nullable', 'string'],
            User::EMAIL => ['nullable', 'string', 'email'],
            User::PASSWORD => ['required', 'string'],
        ]);

        // Validation: Username (x)or email must be provided.
        if (
            (!isset($validated[User::USERNAME]) && !isset($validated[User::EMAIL])) ||
            (isset($validated[User::USERNAME]) && isset($validated[User::EMAIL]))
        ) {
            return response()->json([
                'message' => 'Username (x)or e-mail is required.',
            ], 400);
        }

        // Column we are searching for the user with (either username or e-mail).
        $user_identifier = $validated[User::USERNAME] != null ? User::USERNAME : ($validated[User::EMAIL] ? User::EMAIL : null);
        assert($user_identifier != null);
        $user = User::where($user_identifier, $validated[$user_identifier])->first();

        // Password verification.
        if (!$user || !Hash::check($validated[User::PASSWORD], $user->password)) {
            return response()->json([
                'message' => 'Username/email and/or password are incorrect.',
            ], 401);
        }

        // Issue token.
        $token = $user->createToken('MyoroAPI')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'user_id' => $user->id,
            'token' => $token,
        ], 200);
    }
}
