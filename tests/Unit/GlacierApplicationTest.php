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

    public function testScenarioWithValidOrder(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['vanille']]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('commandes', $result);
        $this->assertArrayHasKey('caisse', $result);
        $this->assertArrayHasKey('stock', $result);
    }

    public function testScenarioWithUnknownFlavour(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['unknown']]);
        
        $this->assertFalse($result['commandes'][0]['succes']);
        $this->assertStringContainsString('inconnue', $result['commandes'][0]['message']);
    }

    public function testScenarioWithMultipleFlavours(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['vanille', 'chocolat']]);
        
        $this->assertCount(1, $result['commandes']);
    }

    public function testScenarioWithMultipleOrders(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['vanille'], ['chocolat']]);
        
        $this->assertCount(2, $result['commandes']);
    }

    public function testCaisseUpdatesAfterSuccessfulOrder(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['vanille']]);
        
        $this->assertGreaterThan(0, $result['caisse']);
    }

    public function testStockUpdatesAfterSuccessfulOrder(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([['vanille']]);
        
        $this->assertIsArray($result['stock']);
    }

    public function testEmptyScenarioReturnsEmptyCommandes(): void
    {
        $app = GlacierApplication::demo();
        $result = $app->traiterScenario([]);
        
        $this->assertEmpty($result['commandes']);
    }
}
