<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', 'en'); // Default to 'en' if no locale is set
        Session::put('locale', $locale); // Store the locale in the session
        App::setLocale($locale); // Set the application locale
        return $next($request);
    }
}
