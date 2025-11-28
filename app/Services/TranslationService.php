<?php

namespace App\Services;

class TranslationService
{
    protected $variants;
    protected $phrases;
    protected $conversations;
    protected $dictionary;

    public function __construct()
    {
        // Cargar archivos de traducción
        $this->variants = include base_path('lang/qu/variants.php');
        $this->phrases = include base_path('lang/qu/phrases.php');
        $this->conversations = include base_path('lang/qu/conversations.php');
        $this->dictionary = include base_path('lang/qu/dictionary.php');
    }

    /**
     * Traduce un mensaje de español a quechua
     * 
     * @param string $text Texto en español a traducir
     * @return string Texto traducido al quechua
     */
    public function translate(string $text): string
    {
        // Normalizar texto
        $originalText = $text;
        $text = trim($text);
        $lowerText = mb_strtolower($text);

        // Prioridad 1: Variantes ortográficas (sin tildes, errores comunes, abreviaciones)
        $translation = $this->searchInVariants($lowerText);
        if ($translation) {
            return $this->preserveCase($translation, $originalText);
        }

        // Prioridad 2: Frases completas específicas de Runamaki
        $translation = $this->searchInPhrases($lowerText);
        if ($translation) {
            return $this->preserveCase($translation, $originalText);
        }

        // Prioridad 3: Conversaciones generales
        $translation = $this->searchInConversations($lowerText);
        if ($translation) {
            return $this->preserveCase($translation, $originalText);
        }

        // Prioridad 4: Traducción palabra por palabra usando diccionario
        $translation = $this->translateWordByWord($lowerText);
        if ($translation !== $lowerText) {
            return $this->preserveCase($translation, $originalText);
        }

        // Si no hay traducción, devolver original
        return $originalText;
    }

    /**
     * Busca en variantes ortográficas
     */
    protected function searchInVariants(string $text): ?string
    {
        foreach ($this->variants as $category => $items) {
            if (isset($items[$text])) {
                return $items[$text];
            }
        }
        return null;
    }

    /**
     * Busca en frases específicas de Runamaki
     */
    protected function searchInPhrases(string $text): ?string
    {
        foreach ($this->phrases as $category => $items) {
            if (isset($items[$text])) {
                return $items[$text];
            }
        }
        return null;
    }

    /**
     * Busca en conversaciones generales
     */
    protected function searchInConversations(string $text): ?string
    {
        foreach ($this->conversations as $category => $items) {
            if (isset($items[$text])) {
                return $items[$text];
            }
        }
        return null;
    }

    /**
     * Traduce palabra por palabra usando el diccionario
     */
    protected function translateWordByWord(string $text): string
    {
        // Separar en palabras
        $words = preg_split('/\s+/', $text);
        $translatedWords = [];

        foreach ($words as $word) {
            // Limpiar puntuación
            $cleanWord = preg_replace('/[^\p{L}\p{N}]/u', '', $word);
            $lowerWord = mb_strtolower($cleanWord);

            // Buscar en diccionario
            $translated = null;
            foreach ($this->dictionary as $category => $items) {
                if (isset($items[$lowerWord])) {
                    $translated = $items[$lowerWord];
                    break;
                }
            }

            // Mantener puntuación original
            if ($translated) {
                $translatedWords[] = str_replace($cleanWord, $translated, $word);
            } else {
                $translatedWords[] = $word;
            }
        }

        return implode(' ', $translatedWords);
    }

    /**
     * Preserva mayúsculas/minúsculas del texto original
     */
    protected function preserveCase(string $translation, string $original): string
    {
        // Si todo está en mayúsculas
        if (mb_strtoupper($original) === $original) {
            return mb_strtoupper($translation);
        }

        // Si la primera letra está en mayúscula
        if (mb_strtoupper(mb_substr($original, 0, 1)) === mb_substr($original, 0, 1)) {
            return mb_strtoupper(mb_substr($translation, 0, 1)) . mb_substr($translation, 1);
        }

        return $translation;
    }

    /**
     * Traduce múltiples mensajes de una sola vez
     * 
     * @param array $messages Array de mensajes a traducir
     * @return array Array de mensajes traducidos
     */
    public function translateBatch(array $messages): array
    {
        return array_map(fn($msg) => $this->translate($msg), $messages);
    }
}
