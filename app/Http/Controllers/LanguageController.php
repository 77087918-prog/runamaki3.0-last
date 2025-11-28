<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Cambia el idioma de la aplicación
     */
    public function change(Request $request)
    {
        $locale = $request->input('locale', 'es');
        
        // Validar que sea un idioma soportado
        if (!in_array($locale, ['es', 'qu'])) {
            return response()->json([
                'success' => false,
                'message' => 'Idioma no soportado'
            ], 400);
        }

        // Guardar en sesión
        session(['locale' => $locale]);

        // Si el usuario está autenticado, guardar en BD
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Idioma cambiado correctamente',
            'locale' => $locale
        ]);
    }

    /**
     * Obtiene el idioma actual
     */
    public function current()
    {
        $locale = app()->getLocale();
        
        return response()->json([
            'locale' => $locale,
            'available' => ['es', 'qu']
        ]);
    }

    /**
     * Traduce un texto usando el TranslationService
     */
    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'to' => 'required|in:es,qu'
        ]);

        $text = $request->input('text');
        $targetLocale = $request->input('to');

        // Si el idioma destino es Quechua, usar el servicio de traducción
        if ($targetLocale === 'qu') {
            $translationService = app(\App\Services\TranslationService::class);
            $translated = $translationService->translate($text);
        } else {
            // Si es a español, devolver el original (ya que los mensajes están en español)
            $translated = $text;
        }

        return response()->json([
            'success' => true,
            'original' => $text,
            'translated' => $translated,
            'locale' => $targetLocale
        ]);
    }
}
