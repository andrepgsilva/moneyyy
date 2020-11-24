<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class ValidateJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->parser()->setRequest(request())->hasToken()) {
            return response()->json(['message' => 'Token not provided.'], 401);
        }
        
        try {
            auth()->parseToken()->refresh();
        } catch (JWTException $e) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        return $next($request);
    }
}
