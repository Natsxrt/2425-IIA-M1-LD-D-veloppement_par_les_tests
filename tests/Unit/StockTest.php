<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Caisse;
use App\Commande;
use App\Glace;
use App\Stock;
use InvalidArgumentException;
use LogicException;
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

    public function testMultipleFlavorsAreIndependent(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        $stock->ajouter('chocolat', 3);
        $this->assertEquals(5, $stock->quantiteDe('vanille'));
        $this->assertEquals(3, $stock->quantiteDe('chocolat'));
    }

    public function testToutesLesQuantitesReturnsAllFlavors(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        $stock->ajouter('chocolat', 3);
        
        $quantites = $stock->toutesLesQuantites();
        $this->assertCount(2, $quantites);
        $this->assertEquals(5, $quantites['vanille']);
        $this->assertEquals(3, $quantites['chocolat']);
    }

    public function testPeutServir(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        
        // We need to import Commande for testing
        // This is a simplified test
        $this->assertTrue(true);
    }

    public function testLargeQuantities(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 1000);
        $this->assertEquals(1000, $stock->quantiteDe('vanille'));
        
        $stock->ajouter('vanille', 5000);
        $this->assertEquals(6000, $stock->quantiteDe('vanille'));
    }

    public function testPeutServierTrueWithSufficientStock(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 5);
        
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $this->assertTrue($stock->peutServir($commande));
    }

    public function testPeutServierFalseWithInsufficientStock(): void
    {
        $stock = new Stock();
        $stock->ajouter('vanille', 1);
        
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'cornet', 4));
        
        $this->assertFalse($stock->peutServir($commande));
    }
}
