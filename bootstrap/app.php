<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 信任代理，确保 HTTPS 正确识别
        $middleware->trustProxies(at: [
            \App\Http\Middleware\TrustProxies::class,
        ]);

        // 将 Token 自动登录中间件添加到 web 中间件组
        $middleware->web(append: [
            \App\Http\Middleware\AutoLoginFromToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
