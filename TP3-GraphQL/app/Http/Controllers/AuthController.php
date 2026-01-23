<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Requests\LoginAuthRequest;
use App\Enumerations\Roles;

class AuthController extends Controller
{
    public function register(RegisterAuthRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['role_id'] = Roles::USER->value; // Role user par défaut.

            $user = User::create($validatedData);

            return response()->json([
                'message' => 'User created successfully',
                'user_id' => $user->id
            ], CREATED);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], INVALID_DATA);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User registration failed'], SERVER_ERROR);
        }
    }

    public function login(LoginAuthRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if (!Auth::attempt($validatedData))
                return response()->json(['message' => 'Authentication failed'], UNAUTHORIZED);

            $token = $this->generateToken(auth()->user());

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], INVALID_DATA);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Login failed'], SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully'], NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Logout failed'], SERVER_ERROR);
        }
    }

    private function generateToken(User $user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
