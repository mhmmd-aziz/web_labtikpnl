<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // <-- Pastikan ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // --- TAMBAHKAN ALIAS DI SINI ---
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckUserRole::class, // <-- Ganti nama class jika berbeda
            // ... alias lain jika ada ...
        ]);
        // -----------------------------

        // Mungkin ada pendaftaran middleware lain di sini (global, group, dll.)
        // $middleware->web(...)
        // $middleware->api(...)

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();