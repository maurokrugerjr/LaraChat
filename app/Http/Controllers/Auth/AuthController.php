<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nome'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'senha'            => ['required', 'string', 'min:8', 'confirmed'],
            'senha_confirmation' => ['required', 'string'],
        ]);

        $user = User::create([
            'nome'  => $data['nome'],
            'email' => $data['email'],
            'senha' => $data['senha'],
        ]);

        $token = auth('api')->login($user);

        return response()->json($this->tokenResponse($token), 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'senha' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->senha, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $token = auth('api')->login($user);

        return response()->json($this->tokenResponse($token));
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function refresh(): JsonResponse
    {
        $token = auth('api')->refresh();

        return response()->json($this->tokenResponse($token));
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    private function tokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user(),
        ];
    }
}
