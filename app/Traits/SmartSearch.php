<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SmartSearch
{
    /**
     * Búsqueda inteligente con scoring y relevancia
     */
    public function smartSearch($query, $fields = [], $options = [])
    {
        $searchTerms = $this->processSearchQuery($query);
        $synonyms = $this->getSynonyms($searchTerms);
        
        return $this->buildSmartQuery($searchTerms, $synonyms, $fields, $options);
    }

    /**
     * Procesar query de búsqueda
     */
    protected function processSearchQuery($query)
    {
        // Limpiar y normalizar el query
        $query = trim(strtolower($query));
        
        // Remover caracteres especiales pero mantener espacios y acentos
        $query = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $query);
        
        // Dividir en términos y filtrar vacíos
        $terms = array_filter(explode(' ', $query));
        
        // Remover stop words en español
        $stopWords = ['el', 'la', 'de', 'que', 'y', 'en', 'un', 'es', 'se', 'no', 'te', 'lo', 'le', 'da', 'su', 'por', 'son', 'con', 'para', 'del', 'una', 'al', 'como', 'las', 'pero', 'sus', 'fue', 'ser', 'tiene', 'todo', 'muy', 'más', 'hace', 'ya', 'hasta', 'dos', 'mi', 'sobre', 'sin', 'fue'];
        
        return array_filter($terms, function($term) use ($stopWords) {
            return strlen($term) > 1 && !in_array($term, $stopWords);
        });
    }

    /**
     * Obtener sinónimos y términos relacionados
     */
    protected function getSynonyms($terms)
    {
        $synonymsMap = [
            // Tecnología
            'programacion' => ['desarrollo', 'codigo', 'software', 'programar', 'programador'],
            'desarrollo' => ['programacion', 'codigo', 'software', 'crear', 'construir'],
            'web' => ['internet', 'sitio', 'pagina', 'online', 'digital'],
            'app' => ['aplicacion', 'movil', 'telefono', 'celular'],
            'base' => ['datos', 'bd', 'database', 'mysql', 'sql'],
            'datos' => ['base', 'database', 'informacion', 'bd'],
            
            // Idiomas
            'ingles' => ['english', 'idioma', 'lengua'],
            'español' => ['castellano', 'idioma', 'lengua'],
            'frances' => ['french', 'idioma', 'lengua'],
            'idioma' => ['lengua', 'hablar', 'comunicacion'],
            'traducir' => ['traduccion', 'interpretar'],
            
            // Arte y creatividad
            'dibujar' => ['dibujo', 'arte', 'pintar', 'ilustrar'],
            'pintar' => ['pintura', 'arte', 'dibujar', 'color'],
            'diseño' => ['diseñar', 'grafico', 'visual', 'creativo'],
            'fotografia' => ['foto', 'camara', 'imagen', 'retratos'],
            
            // Música
            'musica' => ['tocar', 'instrumento', 'cantar', 'melodia'],
            'guitarra' => ['tocar', 'musica', 'instrumento', 'cuerdas'],
            'piano' => ['tocar', 'musica', 'instrumento', 'teclas'],
            'cantar' => ['voz', 'musica', 'vocal', 'coro'],
            
            // Deportes
            'futbol' => ['deporte', 'pelota', 'equipo', 'jugar'],
            'basquet' => ['basketball', 'deporte', 'pelota', 'cancha'],
            'natacion' => ['nadar', 'agua', 'piscina', 'deporte'],
            'correr' => ['running', 'trotar', 'atletismo', 'deporte'],
            
            // Cocina
            'cocinar' => ['cocina', 'recetas', 'comida', 'chef'],
            'reposteria' => ['dulces', 'postres', 'hornear', 'cocina'],
            'chef' => ['cocinar', 'cocina', 'gastronomia', 'comida'],
            
            // Manualidades
            'manualidades' => ['artesania', 'crear', 'hacer', 'craft'],
            'tejer' => ['tejido', 'lana', 'agujas', 'crochet'],
            'costura' => ['coser', 'ropa', 'maquina', 'tela'],
            
            // Educación
            'enseñar' => ['profesor', 'educacion', 'clases', 'tutor'],
            'matematicas' => ['numeros', 'calculo', 'algebra', 'mates'],
            'fisica' => ['ciencia', 'experimentos', 'laboratorio'],
            'quimica' => ['ciencia', 'experimentos', 'laboratorio'],
            
            // Reparaciones
            'reparar' => ['arreglar', 'reparacion', 'mantenimiento'],
            'electricidad' => ['electricista', 'cables', 'instalacion'],
            'plomeria' => ['plomero', 'tuberias', 'agua', 'reparacion']
        ];

        $expandedTerms = [];
        foreach ($terms as $term) {
            $expandedTerms[] = $term;
            if (isset($synonymsMap[$term])) {
                $expandedTerms = array_merge($expandedTerms, $synonymsMap[$term]);
            }
        }

        return array_unique($expandedTerms);
    }

    /**
     * Construir query inteligente con scoring
     */
    protected function buildSmartQuery($originalTerms, $expandedTerms, $fields, $options = [])
    {
        $query = static::query();

        if (!empty($originalTerms)) {
            $query->where(function ($q) use ($originalTerms, $expandedTerms, $fields) {
                // Búsqueda exacta en título (mayor peso)
                foreach ($originalTerms as $term) {
                    $q->orWhere('titulo', 'like', "%{$term}%");
                }
                
                // Búsqueda en descripción
                foreach ($originalTerms as $term) {
                    $q->orWhere('descripcion', 'like', "%{$term}%");
                }
                
                // Búsqueda con sinónimos (menor peso)
                foreach ($expandedTerms as $term) {
                    if (!in_array($term, $originalTerms)) {
                        $q->orWhere('titulo', 'like', "%{$term}%")
                          ->orWhere('descripcion', 'like', "%{$term}%");
                    }
                }
                
                // Búsqueda en nombre de categoría
                $q->orWhereHas('categoria', function($catQuery) use ($originalTerms, $expandedTerms) {
                    foreach (array_merge($originalTerms, $expandedTerms) as $term) {
                        $catQuery->orWhere('nombre', 'like', "%{$term}%");
                    }
                });
            });
            
            // Agregar ordenamiento por relevancia usando MySQL MATCH AGAINST si está disponible
            $searchString = implode(' ', $originalTerms);
            $query->orderByRaw("
                CASE 
                    WHEN titulo LIKE '%{$searchString}%' THEN 1
                    WHEN titulo LIKE '%{$originalTerms[0]}%' THEN 2
                    WHEN descripcion LIKE '%{$searchString}%' THEN 3
                    WHEN descripcion LIKE '%{$originalTerms[0]}%' THEN 4
                    ELSE 5
                END
            ");
        }

        return $query;
    }

    /**
     * Obtener sugerencias de búsqueda
     */
    public function getSearchSuggestions($query, $limit = 5)
    {
        $suggestions = [];
        
        // Sugerencias basadas en títulos similares
        $titleSuggestions = static::select('titulo')
            ->where('titulo', 'like', "%{$query}%")
            ->limit($limit)
            ->pluck('titulo')
            ->unique()
            ->take($limit);
            
        $suggestions = array_merge($suggestions, $titleSuggestions->toArray());
        
        // Sugerencias de términos comunes
        $commonTerms = [
            'programación web', 'diseño gráfico', 'inglés conversacional',
            'guitarra básica', 'cocina italiana', 'yoga principiantes',
            'fotografía digital', 'excel avanzado', 'marketing digital',
            'repostería casera'
        ];
        
        foreach ($commonTerms as $term) {
            if (stripos($term, $query) !== false && count($suggestions) < $limit) {
                $suggestions[] = $term;
            }
        }
        
        return array_slice(array_unique($suggestions), 0, $limit);
    }

    /**
     * Detectar y corregir errores tipográficos básicos
     */
    protected function correctTypos($term)
    {
        $corrections = [
            // Errores comunes en español
            'programacin' => 'programacion',
            'programaion' => 'programacion',
            'desrrollo' => 'desarrollo',
            'diseño' => 'diseño',
            'muscia' => 'musica',
            'gittarra' => 'guitarra',
            'ingels' => 'ingles',
            'cocnia' => 'cocina',
            'fotografai' => 'fotografia',
            'matemtaicas' => 'matematicas',
        ];
        
        return $corrections[$term] ?? $term;
    }
}