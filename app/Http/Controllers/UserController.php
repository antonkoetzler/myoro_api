<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\UnimplementedException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * Get the model class name.
     *
     * @return string
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->userService->all());
    }

    /**
     * Store implementation.
     *
     * @noinspection PhpUnusedParameterInspection
     */
    public function store(): JsonResponse
    {
        throw new UnimplementedException();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $model = User::find($id);
        if (!$model) {
            throw new ResourceNotFoundException();
        }
        return response()->json($model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        throw new UnimplementedException();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        throw new UnimplementedException();
    }

    /**
     * Signup implementation.
     *
     * @param SignupRequest $request
     *
     * @return JsonResponse
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        try {
            /** @var array<string, string|null> */
            $validatedData = $request->validated();
            $user = $this->userService->signup($validatedData);
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
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            /** @var array<string, string> */
            $validatedData = $request->validated();
            $user = $this->userService->login($validatedData);
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
