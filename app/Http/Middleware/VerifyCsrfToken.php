<?php

namespace Corp\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{

//    protected function tokensMatch($request)
//    {
//        $token = $request->ajax() ? $request->header('X-CSRF-TOKEN') : $request->input('_token');
//
//        return $request->session()->token() == $token;
//
//    }

//    protected function tokensMatch($request)
//    {
//        $token =  $request->session()->token();
//
//        return $token;
//
//    }
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
       '*/',
    ];
}
