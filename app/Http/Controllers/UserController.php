<?php

namespace App\Http\Controllers;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        return response()->json(['user' => $user, 'token' => $user->createToken('apiToken')->plainTextToken], 201);
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->get('email'))->first();

        if (!Hash::check($request->get('password'), $user->password)) {
            throw new InvalidCredentialsException('Bad credentials', 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json([]);
    }

    //@TODO add auth v2 with standartized responses. May be extract logic into requests here
}
