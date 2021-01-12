<?php

namespace App\Http\Controllers\API\ForgotPassword;

use App\Models\User;
use App\Utils\LocaleHandle;
use App\Http\Controllers\Controller;

class TokenMatchController extends Controller
{
    public function index()
    {
        LocaleHandle::getUserLang(request());

        $validatedAttributes = request()->validate([
            'email' => 'required|email|exists:users',
            'password_confirmation_token' => 'required|string',
            'lang' => 'required'
        ]);

        $confirmationToken = $validatedAttributes['password_confirmation_token'];

        $user = User::where('email', $validatedAttributes['email'])->first();

        $userHasPasswordToken = $user->passwordTokens()
            ->where('confirmation_token', $confirmationToken)
            ->count() === 1;
        
        if ($userHasPasswordToken) {
            $user->passwordTokens()->delete();
            return response()->json(['message' => 'The user can reset password!']);
        }

        return response()->json(['error' => trans('forgot.confirmation_code_wrong_or_expired')], 403);
    }
}