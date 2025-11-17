<?php
// Script de prueba para scope aprobadas en Habilidad
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Habilidad;

try {
    $count = Habilidad::aprobadas()->count();
    echo "Habilidades aprobadas: {$count}\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
