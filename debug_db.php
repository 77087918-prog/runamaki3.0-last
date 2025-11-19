<?php
echo "=== DEBUG DATABASE VARIABLES ===\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION', 'NOT_SET') . "\n";
echo "DB_HOST: " . env('DB_HOST', 'NOT_SET') . "\n";
echo "DB_PORT: " . env('DB_PORT', 'NOT_SET') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE', 'NOT_SET') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME', 'NOT_SET') . "\n";
echo "DB_PASSWORD: " . env('DB_PASSWORD', 'NOT_SET') . "\n";
echo "\n=== TRYING CONNECTION ===\n";
try {
    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "✅ CONNECTION SUCCESS!\n";
} catch (Exception $e) {
    echo "❌ CONNECTION FAILED: " . $e->getMessage() . "\n";
}