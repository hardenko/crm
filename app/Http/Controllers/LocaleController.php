<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function switch(string $lang): RedirectResponse
    {
        if (in_array($lang, config('app.available_locales'))) {
            Session::put('locale', $lang);
        }

        return redirect()->back();
    }
}
