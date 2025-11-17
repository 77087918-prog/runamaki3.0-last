<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Educación',
                'descripcion' => 'Enseñanza y tutorías',
                'icono' => 'BookOpen',
                'color' => '#C86F3C'
            ],
            [
                'nombre' => 'Tecnología',
                'descripcion' => 'Servicios tecnológicos y digitales',
                'icono' => 'Laptop',
                'color' => '#5A8B4A'
            ],
            [
                'nombre' => 'Manualidades',
                'descripcion' => 'Trabajos manuales y artesanías',
                'icono' => 'Scissors',
                'color' => '#D4A574'
            ],
            [
                'nombre' => 'Idiomas',
                'descripcion' => 'Clases de idiomas locales e internacionales',
                'icono' => 'Languages',
                'color' => '#8B7355'
            ],
            [
                'nombre' => 'Cocina',
                'descripcion' => 'Preparación de alimentos y gastronomía',
                'icono' => 'ChefHat',
                'color' => '#C86F3C'
            ],
            [
                'nombre' => 'Reparaciones',
                'descripcion' => 'Arreglos y mantenimiento',
                'icono' => 'Wrench',
                'color' => '#5A8B4A'
            ],
            [
                'nombre' => 'Arte',
                'descripcion' => 'Expresiones artísticas',
                'icono' => 'Palette',
                'color' => '#D4A574'
            ],
            [
                'nombre' => 'Música',
                'descripcion' => 'Enseñanza musical e instrumentos',
                'icono' => 'Music',
                'color' => '#8B7355'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}