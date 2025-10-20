<?php
namespace Modules\Market;

class SerialService
{
    public static function generate(): string
    {
        return bin2hex(random_bytes(8)) . '-' . bin2hex(random_bytes(8));
    }
}
