<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch the application language and redirect back.
     */
    public function setLocale(string $locale, Request $request): RedirectResponse
    {
        if (in_array($locale, ['es', 'en'])) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}
