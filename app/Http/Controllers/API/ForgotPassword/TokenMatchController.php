<?php

namespace App\Http\Controllers\API\ForgotPassword;

use App\Models\User;
use App\Http\Controllers\Controller;

class TokenMatchController extends Controller
{
    public function index()
    {
        $validatedAttributes = request()->validate([
            'email' => 'required|email|exists:users',
            'confirmation_token' => 'required|string'
        ]);

        $confirmationToken = $validatedAttributes['confirmation_token'];

        $user = User::where('email', $validatedAttributes['email'])->first();

        $userHasPasswordToken = $user->passwordTokens()
            ->where('confirmation_token', $confirmationToken)
            ->count() === 1;
        
        if ($userHasPasswordToken) {
            $user->passwordTokens()->delete();
            return response()->json(['message' => 'The user can reset password!']);
        }

        return response()->json(['error' => 'User cannot reset the password'], 403);
    }
}