<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testItAddsTwoIntegers(): void
    {
        $calculator = new Calculator();

        self::assertSame(4, $calculator->add(2, 2));
    }
}
