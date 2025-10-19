<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class MarketEscrowTest extends TestCase
{
    public function testMarketControllerAvailable(): void
    {
        $this->assertFileExists(__DIR__ . '/../../modules/Market/MarketController.php');
    }
}
