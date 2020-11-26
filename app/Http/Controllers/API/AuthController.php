<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $credentials = request()->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        return response()->json(['message' => 'User successfully created.'], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request()->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Sorry, your password was incorrect.'], 401);
        }

        $user = auth()->user();

        return $this->respondWithToken($token, [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->respondWithToken('', ['message' => 'Successfully logged out.']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth()->parseToken()->refresh();

        return $this->respondWithToken($token, ['message' => 'Token refreshed.']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $message = '')
    {
        return response()
            ->json($message)
            // auth()->factory()->getTTL() * 60
            ->withCookie(cookie('token', $token, 1));
    }
}
