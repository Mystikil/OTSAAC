<?php
declare(strict_types=1);

use App\Modules\Market\SerialService;
use PHPUnit\Framework\TestCase;

final class SerialServiceTest extends TestCase
{
    public function testGenerate(): void
    {
        $serial = SerialService::generate();
        self::assertTrue(SerialService::validate($serial));
    }
}
