<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Http\Middleware\Authenticate;

use Closure;
use Tymon\JWTAuth\JWT;

class JWTAuthenticate extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
         
        $this->authenticate($request);
        return $next($request);
    }
}