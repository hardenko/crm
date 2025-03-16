<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        if ($request->has('lang') && in_array($request->get('lang'), config('app.available_locales'))) {
            Session::put('locale', $request->get('lang'));
        }

        App::setLocale(Session::get('locale', config('app.locale')));

        return $next($request);
    }
}
