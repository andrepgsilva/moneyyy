<?php

namespace App\Http\Controllers\API\ForgotPassword;

use App\Models\User;
use App\Mail\ForgotPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        $validatedAttributes = request()->validate([
            'email' => 'required|email|exists:users',
            'lang' => 'required|string'
        ]);

        $userEmail = $validatedAttributes['email'];
        $userLang = $validatedAttributes['lang'];

        $numberForConfirmation = random_int(1000, 9999);

        $user = User::where('email', $userEmail)->first();

        $userPasswordTokens = $user->passwordTokens;

        if ($userPasswordTokens->count() == 1) {
            $user->passwordTokens()->delete();
        }

        $user->passwordTokens()->create([
            'confirmation_token' => $numberForConfirmation
        ]);

        Mail::to($user)->locale($userLang)->send(new ForgotPassword([
            'username' => $user->name,
            'confirmationToken' => $numberForConfirmation,
        ]));
    }
}
