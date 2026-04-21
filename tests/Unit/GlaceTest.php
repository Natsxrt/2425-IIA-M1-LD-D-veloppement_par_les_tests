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
}
