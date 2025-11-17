<?php
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
try {
    $response = $kernel->handle($request);
    echo 'Status: ' . $response->getStatusCode() . "\n";
    echo 'Headers: ' . json_encode($response->headers->all()) . "\n";
} catch (Throwable $e) {
    echo "Error: " . get_class($e) . " - " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
$kernel->terminate($request, $response ?? null);
