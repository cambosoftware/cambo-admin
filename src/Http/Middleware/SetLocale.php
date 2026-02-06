<?php

namespace CamboSoftware\CamboAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session, cookie, or browser
        $locale = session('locale')
            ?? $request->cookie('locale')
            ?? $request->getPreferredLanguage(config('app.supported_locales', ['en', 'fr']));

        // Validate locale
        $supportedLocales = config('app.supported_locales', ['en', 'fr']);
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'en');
        }

        // Set locale
        App::setLocale($locale);

        return $next($request);
    }
}
