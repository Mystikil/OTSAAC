<?php
namespace Modules\Characters;

class NameRules
{
    public static function validate(string $name): bool
    {
        $name = trim($name);
        if (strlen($name) < 3 || strlen($name) > 20) {
            return false;
        }
        return (bool) preg_match('/^[A-Za-z]+( [A-Za-z]+)?$/', $name);
    }
}
