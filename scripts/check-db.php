<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$keys = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_CHARSET'];

echo "Database environment\n";
foreach ($keys as $key) {
    echo $key . '=' . config_value($key, '(empty)') . PHP_EOL;
}
echo 'DB_PASS=' . (config_value('DB_PASS') !== '' ? '(set)' : '(empty)') . PHP_EOL;
echo PHP_EOL;

try {
    $pdo = db();

    echo "Connection: OK\n";
    echo 'Current database: ' . (string) $pdo->query('SELECT DATABASE()')->fetchColumn() . PHP_EOL;
    echo 'Current user: ' . (string) $pdo->query('SELECT CURRENT_USER()')->fetchColumn() . PHP_EOL;

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo 'Tables: ' . ($tables === [] ? '(none)' : implode(', ', $tables)) . PHP_EOL;

    foreach (['productos', 'contactos'] as $table) {
        $statement = $pdo->query('SELECT COUNT(*) FROM `' . $table . '`');
        echo $table . ': ' . (string) $statement->fetchColumn() . ' rows' . PHP_EOL;
    }
} catch (Throwable $exception) {
    echo "Connection: FAILED\n";
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}
