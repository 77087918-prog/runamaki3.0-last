<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuracion';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'tipo',
    ];

    /**
     * Obtener valor de configuración por clave
     */
    public static function obtener(string $clave, $default = null)
    {
        $config = static::where('clave', $clave)->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->tipo) {
            'numero' => (int) $config->valor,
            'boolean' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            default => $config->valor,
        };
    }

    /**
     * Establecer valor de configuración
     */
    public static function establecer(string $clave, $valor, string $tipo = 'texto', ?string $descripcion = null)
    {
        return static::updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valor,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
            ]
        );
    }
}
