<?php

namespace App\Http\Middleware;

use App\Commons\Enums\HttpStatus;
use App\Commons\Libs\Http\APIResponse;
use App\Commons\Libs\JWT\JWTAuth;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JWTVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Commons\Libs\JWT\JWTGuard $guard */
        $guard = Auth::guard('jwt');

        if (!$guard->check()) {
            // Cek apakah expired
            if (method_exists($guard, 'tokenExpired') && $guard->tokenExpired()) {
                return APIResponse::toJSON(
                    HttpStatus::Unauthorized,
                    'token expired'
                );
            }

            return APIResponse::toJSON(
                HttpStatus::Unauthorized,
                'invalid or missing token'
            );
        }

        Auth::setUser($guard->user());
        return $next($request);
    }
}
