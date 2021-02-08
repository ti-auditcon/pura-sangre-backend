<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*',
        'web/new-user/store',
        '/flow/confirm-payment',
        '/flow/return-from-payment',
        'flow-return',
        'flow-error',
        'planes/contractables'
    ];
}
