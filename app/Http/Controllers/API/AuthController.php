<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Utils\AuthorizationRequestHandle;

class AuthController extends Controller
{
    protected $authorizationRequest;

    public function __construct()
    {
        $this->authorizationRequest = new AuthorizationRequestHandle();
    }

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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (Hash::check($user->password, $credentials['password'])) {
            return response()->json(['error' => 'Sorry, your password was incorrect.'], 401);
        }

        $this->authorizationRequest->addTokensToClient([
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        return response()->json(['email' => $credentials['email']]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $token = request()->user()->token();
        $token->delete();

        cookie()->queue(cookie()->forget('access_token'));
        cookie()->queue(cookie()->forget('refresh_token'));

        return response()->json((['message' => 'Successfully logged out.']));
    }

    /**
     * Refresh user token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        $this->authorizationRequest->refreshToken();

        return response()->json(['message' => 'Token refreshed successfully.']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
