<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function change($locale)
    {
        // Verificar que el idioma sea válido
        if (!in_array($locale, ['es', 'qu'])) {
            $locale = 'es'; // Fallback al español
        }
        
        // Guardar en sesión
        session(['locale' => $locale]);
        app()->setLocale($locale);
        
        return redirect()->back()->with('success', 
            $locale === 'qu' ? 'Rimaymi tikrasqaña Quechua kaqman!' : '¡Idioma cambiado a Español!'
        );
    }
}
