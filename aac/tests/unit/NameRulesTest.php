<?php
declare(strict_types=1);

use App\Modules\Characters\NameRules;
use PHPUnit\Framework\TestCase;

final class NameRulesTest extends TestCase
{
    public function testValidName(): void
    {
        self::assertTrue(NameRules::validate('Hero One'));
    }

    public function testInvalidName(): void
    {
        self::assertFalse(NameRules::validate('Bad#Name'));
    }
}
