<?php

namespace App\Http\Controllers\API\ForgotPassword;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function index()
    {
        \App::setLocale(request('lang'));

        $validatedAttributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'lang' => 'required',
        ]);

        if ($user = User::where('email', $validatedAttributes['email'])->first()) {
            $password = Hash::make($validatedAttributes['password']);
    
            $user->password = $password;
            $user->save();

            return response()->json(['message' => trans('forgot.password_updated_successfully')], 201);
        }

        return response()->json(['message' => trans('forgot.something_went_wrong_try_later')], 400);
    }
}
