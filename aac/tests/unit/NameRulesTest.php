<?php
require __DIR__ . '/../../app/bootstrap.php';

use Modules\Characters\NameRules;

assert(NameRules::validate('Valid Name') === true);
assert(NameRules::validate('Invalid123') === false);

echo "NameRules tests passed\n";
