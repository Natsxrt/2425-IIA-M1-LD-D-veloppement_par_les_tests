<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Glace;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class GlaceTest extends TestCase
{
    public function testItCreatesAValidIceCream(): void
    {
        $glace = new Glace('vanille', 'vanille', 'pot', 4);

        self::assertSame('vanille', $glace->identifiant());
        self::assertSame('vanille', $glace->saveur());
        self::assertSame('pot', $glace->contenant());
        self::assertSame(4, $glace->prixVente());
    }

    public function testItRejectsAnUnknownContainer(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Glace('vanille', 'vanille', 'assiette', 4);
    }

    public function testItRejectsEmptyIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Glace('', 'vanille', 'pot', 4);
    }

    public function testItRejectsEmptyFlavour(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Glace('vanille', '', 'pot', 4);
    }

    public function testItRejectsZeroPrice(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Glace('vanille', 'vanille', 'pot', 0);
    }

    public function testItRejectsNegativePrice(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Glace('vanille', 'vanille', 'pot', -10);
    }

    public function testCornetContainerIsValid(): void
    {
        $glace = new Glace('fraise', 'fraise', 'cornet', 5);
        $this->assertSame('cornet', $glace->contenant());
    }

    public function testPotContainerIsValid(): void
    {
        $glace = new Glace('pistache', 'pistache', 'pot', 6);
        $this->assertSame('pot', $glace->contenant());
    }

    public function testLargestPriceIsHandledCorrectly(): void
    {
        $glace = new Glace('luxe', 'luxe', 'pot', 1000);
        $this->assertEquals(1000, $glace->prixVente());
    }
}
