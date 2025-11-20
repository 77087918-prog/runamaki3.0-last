<?php

namespace Database\Seeders;

use App\Models\Habilidad;
use App\Models\User;
use Illuminate\Database\Seeder;

class NuevasHabilidadesSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que no existan más de 4 habilidades para evitar duplicados
        if (Habilidad::count() > 4) {
            $this->command->info('⚠️ Las habilidades adicionales ya fueron creadas');
            return;
        }

        // Obtener usuarios existentes
        $users = User::all()->keyBy('email');
        
        if ($users->isEmpty()) {
            $this->command->error('❌ No hay usuarios en la base de datos');
            return;
        }

        $maria = $users->get('maria@example.com');
        $carlos = $users->get('carlos@example.com'); 
        $ana = $users->get('ana@example.com');
        $admin = $users->get('admin@runamaki.com');
        $absalon = $users->get('absalon@example.com');

        $nuevasHabilidades = [
            // Educación adicionales
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 1,
                'titulo' => 'Clases de matemáticas para secundaria',
                'descripcion' => 'Apoyo en álgebra, geometría y trigonometría. Método simple y efectivo.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 1,
                'titulo' => 'Tutorías de química y biología',
                'descripcion' => 'Preparación para exámenes universitarios y refuerzo escolar.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobado',
            ],

            // Tecnología adicionales
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 2,
                'titulo' => 'Creación de páginas web básicas',
                'descripcion' => 'HTML, CSS y JavaScript para principiantes. Crea tu primera web.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 60,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 2,
                'titulo' => 'Configuración de redes WiFi domésticas',
                'descripcion' => 'Instalación y optimización de redes inalámbricas en casa.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobado',
            ],

            // Manualidades adicionales
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 3,
                'titulo' => 'Origami y papiroflexia',
                'descripcion' => 'Arte japonés del doblado de papel. Desde básico hasta avanzado.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 25,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 3,
                'titulo' => 'Creación de joyas artesanales',
                'descripcion' => 'Diseño y elaboración de collares, pulseras y aretes únicos.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobado',
            ],

            // Idiomas adicionales
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 4,
                'titulo' => 'Inglés conversacional básico',
                'descripcion' => 'Práctica de inglés para turismo y trabajo. Enfoque en conversación.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 4,
                'titulo' => 'Francés para viajeros',
                'descripcion' => 'Frases y expresiones útiles para viajar a países francófonos.',
                'horas_ofrecidas' => 1,
                'puntos_sugeridos' => 35,
                'estado' => 'aprobado',
            ],

            // Cocina
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 5,
                'titulo' => 'Cocina peruana tradicional',
                'descripcion' => 'Aprende a preparar ceviche, lomo saltado y otros platos típicos.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 50,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 5,
                'titulo' => 'Repostería casera y tortas',
                'descripcion' => 'Técnicas básicas para hornear pasteles, galletas y postres.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobado',
            ],

            // Reparaciones
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 6,
                'titulo' => 'Plomería básica doméstica',
                'descripcion' => 'Reparación de grifos, desatoros y instalaciones menores.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 60,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $absalon->id,
                'categoria_id' => 6,
                'titulo' => 'Electricidad residencial básica',
                'descripcion' => 'Instalación de enchufes, interruptores y reparaciones eléctricas menores.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 65,
                'estado' => 'aprobado',
            ],

            // Arte
            [
                'usuario_id' => $ana->id,
                'categoria_id' => 7,
                'titulo' => 'Pintura al óleo para principiantes',
                'descripcion' => 'Técnicas básicas de pintura, mezcla de colores y composición.',
                'horas_ofrecidas' => 3,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $admin->id,
                'categoria_id' => 7,
                'titulo' => 'Fotografía digital y composición',
                'descripcion' => 'Fundamentos de fotografía, uso de cámara y edición básica.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 50,
                'estado' => 'aprobado',
            ],

            // Música adicionales
            [
                'usuario_id' => $maria->id,
                'categoria_id' => 8,
                'titulo' => 'Canto y técnica vocal',
                'descripcion' => 'Desarrollo de la voz, respiración y técnicas de canto popular.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 40,
                'estado' => 'aprobado',
            ],
            [
                'usuario_id' => $carlos->id,
                'categoria_id' => 8,
                'titulo' => 'Piano para principiantes',
                'descripcion' => 'Fundamentos del piano, lectura musical y primeras melodías.',
                'horas_ofrecidas' => 2,
                'puntos_sugeridos' => 45,
                'estado' => 'aprobado',
            ],
        ];

        foreach ($nuevasHabilidades as $habilidadData) {
            Habilidad::create($habilidadData);
        }

        $this->command->info("✅ Se agregaron " . count($nuevasHabilidades) . " habilidades adicionales");
    }
}
