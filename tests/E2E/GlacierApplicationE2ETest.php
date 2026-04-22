<?php

declare(strict_types=1);

namespace Tests\E2E;

use App\GlacierApplication;
use PHPUnit\Framework\TestCase;

final class GlacierApplicationE2ETest extends TestCase
{
    public function testCompleteApplicationDemoScenario(): void
    {
        $app = GlacierApplication::demo();

        $result = $app->traiterScenario([
            ['vanille'],
        ]);

        // Vérifier la structure de réponse
        $this->assertArrayHasKey('commandes', $result);
        $this->assertArrayHasKey('caisse', $result);
        $this->assertArrayHasKey('stock', $result);
        
        // Vérifier qu'il y a au moins une commande
        $this->assertNotEmpty($result['commandes']);
    }

    public function testApplicationStateAfterScenario(): void
    {
        $app = GlacierApplication::demo();
        
        $result = $app->traiterScenario([
            ['vanille'],
            ['chocolat'],
        ]);

        // Vérifier que la caisse a augmenté
        $this->assertGreaterThan(0, $result['caisse']);
        
        // Vérifier que le stock a diminué
        $this->assertNotEmpty($result['stock']);
        $this->assertLessThan(2, $result['stock']['vanille']);
    }

    public function testMultipleOrderScenario(): void
    {
        $app = GlacierApplication::demo();
        
        $result = $app->traiterScenario([
            ['vanille'],
            ['chocolat'],
            ['vanille'],
        ]);

        // Vérifier que toutes les commandes ont été traitées
        $this->assertCount(3, $result['commandes']);
        
        // Vérifier que la caisse s'est enrichie
        $this->assertGreaterThan(0, $result['caisse']);
    }

    public function testInsufficientStockInScenario(): void
    {
        $app = GlacierApplication::demo();
        
        // Demander plus que ce qui est en stock
        $result = $app->traiterScenario([
            ['vanille'],
            ['vanille'],
            ['vanille'],
        ]);

        // La dernière commande devrait échouer
        $lastCommand = end($result['commandes']);
        $this->assertFalse($lastCommand['succes']);
    }

    public function testUnknownFlavorInScenario(): void
    {
        $app = GlacierApplication::demo();
        
        $result = $app->traiterScenario([
            ['fraise'],
        ]);

        // La commande doit échouer
        $firstCommand = $result['commandes'][0];
        $this->assertFalse($firstCommand['succes']);
        $this->assertStringContainsString('inconnue', $firstCommand['message']);
    }

    public function testComplexMultiFlavorOrder(): void
    {
        $app = GlacierApplication::demo();
        
        $result = $app->traiterScenario([
            ['vanille', 'chocolat'],
        ]);

        // La commande doit échouer car nous demandons plus que le stock en chocolat
        // Stock: vanille=2, chocolat=1
        // Commande: vanille=1, chocolat=1 (OK, devrait réussir)
        $firstCommand = $result['commandes'][0];
        $this->assertTrue($firstCommand['succes']);
        
        // Vérifier que les deux glaces ont été comptabilisées dans le prix
        $this->assertEquals(8, $firstCommand['prix_total']);
    }
}
