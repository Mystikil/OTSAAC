<?php
require __DIR__ . '/../../app/bootstrap.php';

use Modules\Market\SerialService;

$serial = SerialService::generate();
assert(strlen($serial) > 10);
assert(strpos($serial, '-') !== false);

echo "SerialService tests passed\n";
