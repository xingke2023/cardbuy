<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AutoLoginFromToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 如果已经登录，直接通过
        if (Auth::check()) {
            return $next($request);
        }

        // 从 URL 参数中获取 token
        $token = $request->query('token');

        if ($token) {
            // 验证 token
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken) {
                // 自动登录该用户
                Auth::login($accessToken->tokenable);

                // 可选：将 token 保存到 session，避免每次都传递
                $request->session()->put('api_token', $token);
            }
        } else {
            // 尝试从 session 中获取之前保存的 token
            $token = $request->session()->get('api_token');

            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);

                if ($accessToken && !Auth::check()) {
                    Auth::login($accessToken->tokenable);
                }
            }
        }

        return $next($request);
    }
}
