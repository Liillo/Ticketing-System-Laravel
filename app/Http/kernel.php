<?php

use Symfony\Component\HttpKernel\HttpKernel;

class Kernel extends HttpKernel
{protected $routeMiddleware = [
    // existing route middleware...
    'admin.auth' => \App\Http\Middleware\AdminAuth::class,
];
}

