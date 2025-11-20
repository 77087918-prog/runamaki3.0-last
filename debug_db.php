<?php
echo "=== DEBUG DATABASE VARIABLES ===\n";
echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'NOT_SET') . "\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NOT_SET') . "\n";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'NOT_SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT_SET') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'NOT_SET') . "\n";
echo "DB_PASSWORD: " . ($_ENV['DB_PASSWORD'] ?? 'NOT_SET') . "\n";
echo "\n=== TRYING CONNECTION ===\n";
try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "✅ CONNECTION SUCCESS!\n";
} catch (Exception $e) {
    echo "❌ CONNECTION FAILED: " . $e->getMessage() . "\n";
}