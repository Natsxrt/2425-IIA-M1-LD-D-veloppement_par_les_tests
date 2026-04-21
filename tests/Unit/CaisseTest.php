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

    public function testItInitializesWithGivenAmount(): void
    {
        $caisse = new Caisse(100);

        self::assertSame(100, $caisse->montant());
    }

    public function testItEncashesPositiveAmount(): void
    {
        $caisse = new Caisse();
        $caisse->encaisser(50);

        self::assertSame(50, $caisse->montant());
    }

    public function testItAccumulatesEncashedAmounts(): void
    {
        $caisse = new Caisse();
        $caisse->encaisser(30);
        $caisse->encaisser(20);
        $caisse->encaisser(10);

        self::assertSame(60, $caisse->montant());
    }
}
