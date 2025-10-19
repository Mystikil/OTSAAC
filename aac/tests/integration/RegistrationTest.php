<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class RegistrationTest extends TestCase
{
    public function testRegistrationViewExists(): void
    {
        $this->assertFileExists(__DIR__ . '/../../modules/Accounts/views/register.php');
    }
}
