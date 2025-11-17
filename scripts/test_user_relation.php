<?php
// Script de test rápido para comprobar relación User->habilidades
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::first();
if (!$u) {
    echo "no-user\n";
    exit(0);
}

echo "User: {$u->email}\n";
try {
    $count = $u->habilidades()->count();
    echo "Habilidades count: {$count}\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
