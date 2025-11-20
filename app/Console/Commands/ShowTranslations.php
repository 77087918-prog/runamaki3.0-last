<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ShowTranslations extends Command
{
    protected $signature = 'translations:show {locale=es}';
    protected $description = 'Muestra todas las traducciones disponibles para un idioma';

    public function handle()
    {
        $locale = $this->argument('locale');
        
        if (!in_array($locale, ['es', 'qu'])) {
            $this->error('Idioma no soportado. Usa: es o qu');
            return;
        }
        
        $translationFile = base_path("lang/{$locale}/app.php");
        
        if (!file_exists($translationFile)) {
            $this->error("No se encontró el archivo de traducciones para {$locale}");
            return;
        }
        
        $translations = require $translationFile;
        
        $this->info("Traducciones para: " . ($locale === 'es' ? 'Español' : 'Quechua'));
        $this->line(str_repeat('=', 50));
        
        $this->showTranslations($translations, '');
        
        $this->line('');
        $this->info('Para cambiar idioma en la web, visita:');
        $this->line('- /locale/es (Español)');
        $this->line('- /locale/qu (Quechua)');
    }
    
    private function showTranslations($array, $prefix)
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? $prefix . '.' . $key : $key;
            
            if (is_array($value)) {
                $this->line('');
                $this->warn("📂 {$fullKey}:");
                $this->showTranslations($value, $fullKey);
            } else {
                $this->line("  🔤 {$fullKey}: {$value}");
            }
        }
    }
}