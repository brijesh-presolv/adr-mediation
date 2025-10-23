<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{

    public function handle(Request $request, Closure $next)
    {
        //return $next($request);

        $origin = $request->headers->get('Origin');
        $referer = $request->headers->get('Referer');
        $allowed_domains = ['https://ukmediation.presolv360.com', 'https://apiukmediation.presolv360.com', 'https://testing.ukmediation.presolv360.com', 'https://testmed.presolv360.com', 'http://localhost:3000', 'http://localhost'];

      if ($origin && in_array($origin, $allowed_domains)) {
            $allowedOrigin = $origin;

        } elseif ($referer) {

            $refererOrigin = parse_url($referer, PHP_URL_SCHEME) . '://' . parse_url($referer, PHP_URL_HOST);
            if (in_array($refererOrigin, $allowed_domains)) {
                $allowedOrigin = $refererOrigin;
            }
        }
        
        $response = $next($request);
        if ($allowedOrigin && in_array($allowedOrigin, $allowed_domains)) {

            $response->headers->set('Access-Control-Allow-Origin' , $allowedOrigin);
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        }
        return $response;

    }
}
