<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUser;
use App\Http\Requests\Login as LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(CreateUser $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json(['data' => [
            'user' => new UserResource($user),
            'token' => $user->createToken('authToken')->accessToken,
        ]], 201);
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! \Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid e-mail or password'], 401);
        }

        $user = \Auth::user();
        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['data' => [
            'user' => new UserResource($user),
            'token' => $token,
        ]], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully']);
    }
}
