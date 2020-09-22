<?php

namespace sub100\Auth\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTVerifier extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {

        $token = $request->header('x-api-key');

        if(!$token) {
            return response()->json(['status' => 'Sub100 Authorization Token not found'], 401);
        }

        try {
            JWTAuth::setToken($token)->getPayload();
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['status' => 'Sub100 Token is invalid'], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json(['status' => 'Sub100 Token is expired'], 401);
            } else {
                return response()->json(['status' => 'Sub100 Authorization Token not found'], 401);
            }
        }

        return $next($request);
    }
}
