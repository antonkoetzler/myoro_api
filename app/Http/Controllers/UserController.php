<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(User::all());
    }

    /**
     * Store implementation.
     */
    public function store(Request $request): JsonResponse
    {
        throw new \Exception('Not implemented', 501);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $model = User::findOrFail($id);
        if (!$model) {
            return response()->json(
                ['message' => 'User not found.'],
                404,
            );
        }
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        throw new \Exception('Not implemented', 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        throw new \Exception('Not implemented', 501);
    }

    /**
     * Signup implementation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->signup($request->validated);
            $token = $user->createToken('MyoroAPI')->plainTextToken;
            return response()->json([
                'message' => 'User registered successfully!',
                'user_id' => $user->id,
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode(),
            );
        }
    }

    /**
     * Login implementation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->login($request->validated);
            $token = $user->createToken('MyoroAPI')->plainTextToken;
            return response()->json([
                'message' => 'Login successful!',
                'user_id' => $user->id,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                $e->getCode(),
            );
        }
    }
}
