<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SetupFlowTest extends TestCase
{
    public function testSetupStepsDefined(): void
    {
        $this->assertFileExists(__DIR__ . '/../../modules/Setup/views/welcome.php');
        $this->assertFileExists(__DIR__ . '/../../modules/Setup/schema/base.sql');
    }
}
