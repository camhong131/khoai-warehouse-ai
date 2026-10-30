<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Bổ sung middleware xác thực/phân quyền khi phát triển backend.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Sử dụng cơ chế xử lý ngoại lệ mặc định của Laravel.
    })->create();
