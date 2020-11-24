<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyEmailController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            !! User::whereEmail($request->only('email'))->get()->count()
        );
    }
}
