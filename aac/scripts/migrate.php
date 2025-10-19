<?php
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

use App\DB;

$config = require __DIR__ . '/../config/database.php';
DB::configure($config);

$schemaDir = __DIR__ . '/../modules/Setup/schema';
$version = $argv[1] ?? '1098';
$files = ['base.sql'];
$files[] = $version === '860' ? '860.sql' : '1098.sql';

$pdo = DB::getConnection();
foreach ($files as $file) {
    $sql = (string)file_get_contents($schemaDir . '/' . $file);
    $sql = str_replace('{{prefix}}', $config['prefix'], $sql);
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        if ($statement !== '') {
            $pdo->exec($statement);
        }
    }
}

echo "Migrations applied\n";
