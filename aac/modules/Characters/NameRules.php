<?php
declare(strict_types=1);

namespace App\Modules\Characters;

final class NameRules
{
    public static function validate(string $name): bool
    {
        $name = trim($name);
        if (strlen($name) < 3 || strlen($name) > 20) {
            return false;
        }
        if (!preg_match('/^[A-Za-z]+(?: [A-Za-z]+)?$/', $name)) {
            return false;
        }
        return true;
    }
}
