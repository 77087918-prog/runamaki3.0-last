<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Logro;
use App\Models\Configuracion;
use App\Models\Habilidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear categorías
        $categorias = [
            ['nombre' => 'Educación', 'descripcion' => 'Enseñanza y tutorías', 'icono' => 'BookOpen', 'color' => '#C86F3C'],
            ['nombre' => 'Tecnología', 'descripcion' => 'Servicios tecnológicos y digitales', 'icono' => 'Laptop', 'color' => '#5A8B4A'],
            ['nombre' => 'Manualidades', 'descripcion' => 'Trabajos manuales y artesanías', 'icono' => 'Scissors', 'color' => '#D4A574'],
            ['nombre' => 'Idiomas', 'descripcion' => 'Clases de idiomas locales e internacionales', 'icono' => 'Languages', 'color' => '#8B7355'],
            ['nombre' => 'Cocina', 'descripcion' => 'Preparación de alimentos y gastronomía', 'icono' => 'ChefHat', 'color' => '#C86F3C'],
            ['nombre' => 'Reparaciones', 'descripcion' => 'Arreglos y mantenimiento', 'icono' => 'Wrench', 'color' => '#5A8B4A'],
            ['nombre' => 'Arte', 'descripcion' => 'Expresiones artísticas', 'icono' => 'Palette', 'color' => '#D4A574'],
            ['nombre' => 'Música', 'descripcion' => 'Enseñanza musical e instrumentos', 'icono' => 'Music', 'color' => '#8B7355'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        // 2. Crear logros
        $logros = [
            ['nombre' => 'Primer Trueque', 'descripcion' => 'Completaste tu primer intercambio', 'icono' => '🎉', 'requisito_tipo' => 'trueques', 'requisito_valor' => 1],
            ['nombre' => '10 Trueques', 'descripcion' => 'Has realizado 10 trueques exitosos', 'icono' => '🌟', 'requisito_tipo' => 'trueques', 'requisito_valor' => 10],
            ['nombre' => '50 Trueques', 'descripcion' => 'Experto en intercambios', 'icono' => '🏆', 'requisito_tipo' => 'trueques', 'requisito_valor' => 50],
            ['nombre' => '100 Trueques', 'descripcion' => 'Maestro del trueque', 'icono' => '💎', 'requisito_tipo' => 'trueques', 'requisito_valor' => 100],
            ['nombre' => '100 Puntos Runa', 'descripcion' => 'Acumulaste 100 puntos', 'icono' => '💰', 'requisito_tipo' => 'puntos', 'requisito_valor' => 100],
            ['nombre' => '500 Puntos Runa', 'descripcion' => 'Eres un acumulador', 'icono' => '💵', 'requisito_tipo' => 'puntos', 'requisito_valor' => 500],
            ['nombre' => 'Mentor Comunitario', 'descripcion' => 'Reputación excelente', 'icono' => '👨‍🏫', 'requisito_tipo' => 'reputacion', 'requisito_valor' => 48],
            ['nombre' => 'Experto Local', 'descripcion' => 'Referente en tu comunidad', 'icono' => '⭐', 'requisito_tipo' => 'reputacion', 'requisito_valor' => 50],
        ];

        foreach ($logros as $logro) {
            Logro::create($logro);
        }

        // 3. Crear configuración
        $configuraciones = [
            ['clave' => 'nombre_sitio', 'valor' => 'Runa Maki', 'descripcion' => 'Nombre de la plataforma', 'tipo' => 'texto'],
            ['clave' => 'puntos_inicial', 'valor' => '100', 'descripcion' => 'Puntos Runa al registrarse', 'tipo' => 'numero'],
            ['clave' => 'puntos_por_hora', 'valor' => '25', 'descripcion' => 'Puntos sugeridos por hora de servicio', 'tipo' => 'numero'],
            ['clave' => 'moderacion_activa', 'valor' => 'false', 'descripcion' => 'Requiere aprobación de habilidades', 'tipo' => 'boolean'],
        ];

        foreach ($configuraciones as $config) {
            Configuracion::create($config);
        }

        // 4. Crear usuarios
        // Administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@runamaki.com',
            'password' => bcrypt('admin123'),
            'rol' => 'admin',
            'puntos_runa' => 1000,
            'reputacion' => 5.00,
            'nivel' => 'Principiante',
            'estado' => 'activo',
            'ubicacion' => 'Cusco, Perú',
        ]);

        // Usuarios de ejemplo
        $maria = User::create([
            'name' => 'María Quispe',
            'email' => 'maria@example.com',
            'password' => bcrypt('admin123'),
            'puntos_runa' => 180,
            'reputacion' => 4.90,
            'nivel' => 'Principiante',
            'estado' => 'activo',
            'ubicacion' => 'Cusco, Perú',
        ]);

        $carlos = User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos@example.com',
            'password' => bcrypt('admin123'),
            'puntos_runa' => 320,
            'reputacion' => 5.00,
            'nivel' => 'Principiante',
            'estado' => 'activo',
            'ubicacion' => 'Cusco, Perú',
        ]);

        $ana = User::create([
            'name' => 'Ana Torres',
            'email' => 'ana@example.com',
            'password' => bcrypt('admin123'),
            'puntos_runa' => 150,
            'reputacion' => 4.70,
            'nivel' => 'Principiante',
            'estado' => 'activo',
            'ubicacion' => 'Cusco, Perú',
        ]);

        $absalon = User::create([
            'name' => 'Absalón',
            'email' => 'absalon@example.com',
            'password' => bcrypt('admin123'),
            'puntos_runa' => 250,
            'reputacion' => 4.80,
            'nivel' => 'Principiante',
            'estado' => 'activo',
            'ubicacion' => 'Cusco, Perú',
        ]);

        // 5. Crear habilidades de ejemplo
        Habilidad::create([
            'usuario_id' => $maria->id,
            'categoria_id' => 4, // Idiomas
            'titulo' => 'Clases de Quechua para principiantes',
            'descripcion' => 'Aprende el idioma ancestral de los Andes. Clases didácticas y culturales.',
            'horas_ofrecidas' => 2,
            'puntos_sugeridos' => 30,
            'estado' => 'aprobado',
        ]);

        Habilidad::create([
            'usuario_id' => $carlos->id,
            'categoria_id' => 2, // Tecnología
            'titulo' => 'Reparación de laptops y PCs',
            'descripcion' => 'Diagnóstico y solución de problemas de hardware y software.',
            'horas_ofrecidas' => 1,
            'puntos_sugeridos' => 50,
            'estado' => 'aprobado',
        ]);

        Habilidad::create([
            'usuario_id' => $maria->id,
            'categoria_id' => 3, // Manualidades
            'titulo' => 'Tejido tradicional andino',
            'descripcion' => 'Enseñanza de técnicas ancestrales de tejido cusqueño.',
            'horas_ofrecidas' => 3,
            'puntos_sugeridos' => 40,
            'estado' => 'aprobado',
        ]);

        Habilidad::create([
            'usuario_id' => $absalon->id,
            'categoria_id' => 8, // Música
            'titulo' => 'Clases de guitarra nivel básico',
            'descripcion' => 'Aprende a tocar guitarra desde cero con métodos prácticos.',
            'horas_ofrecidas' => 2,
            'puntos_sugeridos' => 35,
            'estado' => 'aprobado',
        ]);

        $this->command->info('✅ Base de datos poblada correctamente con:');
        $this->command->info('   - 8 categorías');
        $this->command->info('   - 8 logros');
        $this->command->info('   - 4 configuraciones');
        $this->command->info('   - 5 usuarios (1 admin + 4 usuarios)');
        $this->command->info('   - 4 habilidades');
        $this->command->info('   Contraseña para todos los usuarios: admin123');
    }
}
