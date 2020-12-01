<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class AddTokenToAuthHeader
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
        if (! $accessToken = $request->cookie('access_token')) {
            return response()->json(['error' => 'Missing access token'], 401);
        }

        $request->headers->add(['Authorization' => 'Bearer ' . $accessToken]);

        return $next($request);
    }
}
