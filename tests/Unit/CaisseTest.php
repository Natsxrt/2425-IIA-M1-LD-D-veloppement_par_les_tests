<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Caisse;
use PHPUnit\Framework\TestCase;

final class CaisseTest extends TestCase
{
    public function testItInitializesWithZeroByDefault(): void
    {
        $caisse = new Caisse();

        self::assertSame(0, $caisse->montant());
    }
}
