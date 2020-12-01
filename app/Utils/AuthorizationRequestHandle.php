<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AuthorizationRequestHandle 
{
    protected function defaultFormFields()
    {
        return [
            'client_id' => config('app.passport_client_id'),
            'client_secret' => config('app.passport_client_secret'),
            'scope' => '*',
        ];
    }

    public function addTokensToClient($credentials)
    {
        $defaultForm = $this->defaultFormFields();
        $defaultForm['grant_type'] = 'password';

        $requestForm = array_merge($defaultForm, $credentials);
        $tokenRequest = Request::create(config('url') . '/oauth/token', 'post', $requestForm);
        
        $responseBody = json_decode(app()->handle($tokenRequest)->getContent());
        
        $accessToken = $responseBody->access_token;
        $refreshToken = $responseBody->refresh_token;

        Cookie::queue(Cookie::make('access_token', $accessToken, 1));
        Cookie::queue(Cookie::make('refresh_token', $refreshToken, 3600 * 24 * 14));
    }

    public function refreshToken()
    {
        $refreshToken = request()->cookie('refresh_token');

        if (! $refreshToken) {
            return response()->json(['message' => 'Refresh token is expired']);
        }

        $paramsToRequestRefresh = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return $this->addTokensToClient($paramsToRequestRefresh);
    }
}