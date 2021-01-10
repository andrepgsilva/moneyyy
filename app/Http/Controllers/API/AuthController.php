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
        \App::setLocale(request('lang'));
        
        $credentials = request()->validate([
            'name' => 'required|max:255|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'lang' => 'required|max:255',
        ]);

        User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'timezone' => $credentials['lang'],
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
        \App::setLocale(request('lang'));

        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device' => 'required|string',
            'lang' => 'required',
        ]);

        $lang = $credentials['lang'];

        if (! $user = User::where('email', $credentials['email'])->first()) {
            return response()->json(['error' => trans('login.email_does_not_belong', [], $lang)], 401);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => trans('login.incorrect_password', [], $lang)], 401);
        }

        $clientTokens = $this->authorizationRequest->addTokensToClient($credentials);

        if (empty($clientTokens)) {
            return response()->json(['error' => 'Invalid token.'], 401);
        };

        $isNotMobile = $credentials['device'] !== 'mobile';

        return response()->json($isNotMobile ? $clientTokens->only('email') : $clientTokens);
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
        if (! $this->authorizationRequest->refreshToken()) {
            return response()->json(['error' => 'Refresh token is expired'], 401);
        }

        return response()->json(['message' => 'Token refreshed successfully.']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
