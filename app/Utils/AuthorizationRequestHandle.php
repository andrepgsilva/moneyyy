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
            'device' => 'web',
        ];
    }
    /**
     * Add tokens to client
     *
     * Add tokens to client that did a auth request
     *
     * @param Array $credentials
     * 
     * @return Collection
     **/
    public function addTokensToClient($credentials)
    {
        $defaultForm = $this->defaultFormFields();
        $defaultForm['grant_type'] = 'password';

        $credentials['username'] = $credentials['email'];
        unset($credentials['email']);

        $requestForm = array_merge($defaultForm, $credentials);
        $tokenRequest = Request::create(config('url') . '/oauth/token', 'post', $requestForm);
        
        $responseBody = json_decode(app()->handle($tokenRequest)->getContent());
        
        $areBothTokensValid = isset($responseBody->access_token) && isset($responseBody->refresh_token); 
        
        if (! $areBothTokensValid) return [];

        $accessToken = $responseBody->access_token;
        $refreshToken = $responseBody->refresh_token;

        Cookie::queue(Cookie::make('access_token', $accessToken, config('jwt.ttl_token') ?? 5));
        Cookie::queue(Cookie::make('refresh_token', $refreshToken, config('jwt.refresh_token') ?? 20160));

        return collect([
            'access_token' => $accessToken, 
            'refresh_token' => $refreshToken, 
            'email' => $credentials['email']
        ]);
    }

    public function refreshToken()
    {
        if (! $refreshToken = request()->cookie('refresh_token')) {
            return false;
        }

        $paramsToRequestRefresh = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return $this->addTokensToClient($paramsToRequestRefresh);
    }
}