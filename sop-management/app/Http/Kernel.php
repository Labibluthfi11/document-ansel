<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        // tambahkan middleware lain di sini
    ];

    // Middleware groups, route middleware, dsb tetap default
}
