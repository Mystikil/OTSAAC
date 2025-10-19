<?php
declare(strict_types=1);

use App\Security;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    public function testPasswordHashing(): void
    {
        $hash = Security::hashPassword('secret123', ['security' => ['hash' => []]]);
        self::assertTrue(Security::verifyPassword('secret123', $hash));
    }
}
