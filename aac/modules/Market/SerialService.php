<?php
declare(strict_types=1);

namespace App\Modules\Market;

final class SerialService
{
    public static function generate(): string
    {
        return bin2hex(random_bytes(16));
    }

    public static function validate(string $serial): bool
    {
        return (bool)preg_match('/^[a-f0-9]{32}$/', $serial);
    }
}
