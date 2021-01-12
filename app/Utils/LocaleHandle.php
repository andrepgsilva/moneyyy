<?php

namespace App\Utils;

class LocaleHandle 
{
    /**
     * Get user language
     *
     * @param Illuminate\Http\Request $request

     * @return string
     **/
    public static function getUserLang($request)
    {
        $userLanguage = $request->has('lang') ? $request->lang : 'en';
        \App::setLocale($userLanguage);

        return $userLanguage;
    }
}