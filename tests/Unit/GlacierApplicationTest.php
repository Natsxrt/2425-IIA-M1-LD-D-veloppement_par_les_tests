<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\GlacierApplication;
use PHPUnit\Framework\TestCase;

final class GlacierApplicationTest extends TestCase
{
    public function testDemoCreatesValidApplication(): void
    {
        $app = GlacierApplication::demo();
        $this->assertInstanceOf(GlacierApplication::class, $app);
    }
}
