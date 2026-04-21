<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Stock;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StockTest extends TestCase
{
    public function testStartsEmpty(): void
    {
        $stock = new Stock();
        $this->assertEquals(0, $stock->quantiteDe('vanille'));
    }

    public function testAddsQuantity(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        $this->assertEquals(5, $stock->quantiteDe('vanille'));
    }

    public function testRejectsEmptyIdentifier(): void
    {
        $stock = new Stock();
        $this->expectException(InvalidArgumentException::class);
        $stock->ajouter('', 5);
    }

    public function testRejectsZeroQuantity(): void
    {
        $stock = new Stock();
        $this->expectException(InvalidArgumentException::class);
        $stock->ajouter('vanille', 0);
    }

    public function testRejectsNegativeQuantity(): void
    {
        $stock = new Stock();
        $this->expectException(InvalidArgumentException::class);
        $stock->ajouter('vanille', -10);
    }

    public function testAccumulatesQuantity(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        $stock->ajouter('vanille', 3);
        $this->assertEquals(8, $stock->quantiteDe('vanille'));
    }
}
