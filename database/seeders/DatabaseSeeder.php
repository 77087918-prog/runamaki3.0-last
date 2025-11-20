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
        $habilidades = [
            // Educación
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 1,
                'titulo' => 'Clases de matemáticas para secundaria',
                'descripcion' => 'Apoyo en álgebra, geometría y trigonometría. Método simple y efectivo.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 1,
                'titulo' => 'Tutorías de química y biología',
                'descripcion' => 'Preparación para exámenes universitarios y refuerzo escolar.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 1,
                'titulo' => 'Comprensión lectora para niños',
                'descripcion' => 'Técnicas para mejorar la lectura y comprensión en niños de 6-12 años.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 30,
                'estado' => 'aprobada',
            ],

            // Tecnología
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 2,
                'titulo' => 'Reparación de laptops y PCs',
                'descripcion' => 'Diagnóstico y solución de problemas de hardware y software.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 50,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 2,
                'titulo' => 'Creación de páginas web básicas',
                'descripcion' => 'HTML, CSS y JavaScript para principiantes. Crea tu primera web.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 60,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 2,
                'titulo' => 'Configuración de redes WiFi domésticas',
                'descripcion' => 'Instalación y optimización de redes inalámbricas en casa.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 2,
                'titulo' => 'Recuperación de datos perdidos',
                'descripcion' => 'Servicios de recuperación de archivos de discos duros y memorias.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 70,
                'estado' => 'aprobada',
            ],

            // Manualidades
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 3,
                'titulo' => 'Tejido tradicional andino',
                'descripcion' => 'Enseñanza de técnicas ancestrales de tejido cusqueño.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 3,
                'titulo' => 'Origami y papiroflexia',
                'descripcion' => 'Arte japonés del doblado de papel. Desde básico hasta avanzado.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 25,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 3,
                'titulo' => 'Creación de joyas artesanales',
                'descripcion' => 'Diseño y elaboración de collares, pulseras y aretes únicos.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 3,
                'titulo' => 'Decoración con plantas suculentas',
                'descripcion' => 'Crea hermosos arreglos y terrarios con plantas resistentes.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 20,
                'estado' => 'aprobada',
            ],

            // Idiomas
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 4,
                'titulo' => 'Clases de Quechua para principiantes',
                'descripcion' => 'Aprende el idioma ancestral de los Andes. Clases didácticas y culturales.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 30,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 4,
                'titulo' => 'Inglés conversacional básico',
                'descripcion' => 'Práctica de inglés para turismo y trabajo. Enfoque en conversación.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 4,
                'titulo' => 'Francés para viajeros',
                'descripcion' => 'Frases y expresiones útiles para viajar a países francófonos.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobada',
            ],

            // Cocina
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 5,
                'titulo' => 'Cocina peruana tradicional',
                'descripcion' => 'Aprende a preparar ceviche, lomo saltado y otros platos típicos.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 50,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 5,
                'titulo' => 'Repostería casera y tortas',
                'descripcion' => 'Técnicas básicas para hornear pasteles, galletas y postres.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 5,
                'titulo' => 'Preparación de comida vegetariana',
                'descripcion' => 'Recetas saludables y nutritivas sin carne. Incluye proteínas vegetales.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 5,
                'titulo' => 'Panadería artesanal en casa',
                'descripcion' => 'Elaboración de panes caseros, masa madre y técnicas de fermentación.',
                'horas_ofrecidas' => 4,
                'puntos_sugeridos' => 55,
                'estado' => 'aprobada',
            ],

            // Reparaciones
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 6,
                'titulo' => 'Plomería básica doméstica',
                'descripcion' => 'Reparación de grifos, desatoros y instalaciones menores.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 60,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 6,
                'titulo' => 'Electricidad residencial básica',
                'descripcion' => 'Instalación de enchufes, interruptores y reparaciones eléctricas menores.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 65,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 6,
                'titulo' => 'Reparación de electrodomésticos',
                'descripcion' => 'Diagnóstico y arreglo de lavadoras, refrigeradoras y hornos microondas.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 70,
                'estado' => 'aprobada',
            ],

            // Arte
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 7,
                'titulo' => 'Pintura al óleo para principiantes',
                'descripcion' => 'Técnicas básicas de pintura, mezcla de colores y composición.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 7,
                'titulo' => 'Dibujo artístico y retrato',
                'descripcion' => 'Desarrollo de habilidades de dibujo realista y técnicas de sombreado.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 7,
                'titulo' => 'Fotografía digital y composición',
                'descripcion' => 'Fundamentos de fotografía, uso de cámara y edición básica.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 50,
                'estado' => 'aprobada',
            ],

            // Música
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 8,
                'titulo' => 'Clases de guitarra nivel básico',
                'descripcion' => 'Aprende a tocar guitarra desde cero con métodos prácticos.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 8,
                'titulo' => 'Canto y técnica vocal',
                'descripcion' => 'Desarrollo de la voz, respiración y técnicas de canto popular.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 8,
                'titulo' => 'Piano para principiantes',
                'descripcion' => 'Fundamentos del piano, lectura musical y primeras melodías.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobada',
            ],
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 8,
                'titulo' => 'Charango y música andina',
                'descripcion' => 'Aprende a tocar el charango y ritmos tradicionales de los Andes.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobada',
            ],
        ];

        foreach ($habilidades as $habilidadData) {
            Habilidad::create($habilidadData);
        }

        $this->command->info('✅ Base de datos poblada correctamente con:');
        $this->command->info('   - 8 categorías');
        $this->command->info('   - 8 logros');
        $this->command->info('   - 4 configuraciones');
        $this->command->info('   - 5 usuarios (1 admin + 4 usuarios)');
        $this->command->info('   - 27 habilidades diversas');
        $this->command->info('   Contraseña para todos los usuarios: admin123');
    }
}
