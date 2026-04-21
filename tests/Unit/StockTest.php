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
}
