<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Caisse;
use App\Commande;
use App\Glace;
use App\ServiceCommande;
use App\Stock;
use PHPUnit\Framework\TestCase;

final class OrderProcessingTest extends TestCase
{
    public function testCompleteOrderProcessing(): void
    {
        $service = new ServiceCommande();
        $stock = new Stock();
        $caisse = new Caisse(50);
        
        $stock->ajouter('vanille', 10);
        $stock->ajouter('chocolat', 8);
        
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 5));
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 6));
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals(61, $caisse->montant());
        $this->assertEquals(9, $stock->quantiteDe('vanille'));
        $this->assertEquals(7, $stock->quantiteDe('chocolat'));
        $this->assertTrue($commande->estLivree());
    }

    public function testMultipleOrdersSequence(): void
    {
        $service = new ServiceCommande();
        $stock = new Stock();
        $caisse = new Caisse(0);
        
        $stock->ajouter('vanille', 5);
        
        // Première commande
        $commande1 = new Commande();
        $commande1->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 10));
        
        $result1 = $service->traiter($commande1, $stock, $caisse);
        $this->assertTrue($result1->succesExecution());
        $this->assertEquals(10, $caisse->montant());
        $this->assertEquals(4, $stock->quantiteDe('vanille'));
        
        // Deuxième commande
        $commande2 = new Commande();
        $commande2->ajouterGlace(new Glace('vanille', 'vanille', 'cornet', 8));
        
        $result2 = $service->traiter($commande2, $stock, $caisse);
        $this->assertTrue($result2->succesExecution());
        $this->assertEquals(18, $caisse->montant());
        $this->assertEquals(3, $stock->quantiteDe('vanille'));
    }

    public function testInsufficientStockRefusalPath(): void
    {
        $service = new ServiceCommande();
        $stock = new Stock();
        $caisse = new Caisse(100);
        
        $stock->ajouter('vanille', 2);
        $stock->ajouter('chocolat', 1);
        
        $commande = new Commande();
        // Demander 3 glaces vanille (stock en a 2)
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 5));
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 5));
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 5));
        // Demander 2 glaces chocolat (stock en a 1)
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 6));
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 6));
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertFalse($result->succesExecution());
        // Stock ne doit pas changer en cas d'échec
        $this->assertEquals(2, $stock->quantiteDe('vanille'));
        $this->assertEquals(1, $stock->quantiteDe('chocolat'));
        // Caisse ne doit pas changer en cas d'échec
        $this->assertEquals(100, $caisse->montant());
        // Commande ne doit pas être livrée
        $this->assertFalse($commande->estLivree());
    }
}
