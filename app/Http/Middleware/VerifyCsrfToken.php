<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'login',
        'logout',
        'admin/dashboard',
        'api/*',
        'users',        // Ajouté
        'users/*',     // Ajouté
        // temporaire pour tests API
        'clients',
        'clients/*',
        'clients/*/edit',
        'change-language'

    ];
}
