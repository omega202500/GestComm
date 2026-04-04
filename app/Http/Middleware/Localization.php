<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
class Localization
{
    public function handle($request, Closure $next)
    {
        // Vérifier si une langue est stockée en session
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['fr', 'en', 'es'])) {
                App::setLocale($locale);
            }
        } else {
            // Langue par défaut
            App::setLocale('fr');
        }

        return $next($request);
    }
}
