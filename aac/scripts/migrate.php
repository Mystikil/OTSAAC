<?php
require __DIR__ . '/../app/bootstrap.php';

use Modules\Setup\SetupController;

$config = App\config('database');
$schemaVersion = $config['schema_version'] ?? '1098';
$pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['dbname']), $config['user'], $config['pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$controller = new SetupController();
$refClass = new ReflectionClass($controller);
$method = $refClass->getMethod('runMigrations');
$method->setAccessible(true);
$method->invoke($controller, $pdo, $config['table_prefix'], $schemaVersion);

echo "Migrations complete\n";
