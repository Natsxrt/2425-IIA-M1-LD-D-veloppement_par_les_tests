<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Caisse;
use App\Commande;
use App\Glace;
use App\ServiceCommande;
use App\Stock;
use PHPUnit\Framework\TestCase;

final class ServiceCommandeTest extends TestCase
{
    public function testRefusesEmptyOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $stock = new Stock();
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertFalse($result->succesExecution());
        $this->assertStringContainsString('vide', $result->message());
    }

    public function testRefusesOrderWithInsufficientStock(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertFalse($result->succesExecution());
        $this->assertStringContainsString('stock', $result->message());
    }

    public function testAcceptsValidOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertTrue($result->succesExecution());
    }

    public function testUpdatesStockAfterValidOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $caisse = new Caisse();
        
        $service->traiter($commande, $stock, $caisse);
        
        $this->assertEquals(9, $stock->quantiteDe('vanille'));
    }

    public function testUpdatesCaisseAfterValidOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $caisse = new Caisse();
        
        $service->traiter($commande, $stock, $caisse);
        
        $this->assertEquals(4, $caisse->montant());
    }

    public function testDeliversOrderAfterValidOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $caisse = new Caisse();
        
        $service->traiter($commande, $stock, $caisse);
        
        $this->assertTrue($commande->estLivree());
    }

    public function testHandlesMultipleItemOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 5));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $stock->ajouter('chocolat', 10);
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals(9, $caisse->montant());
    }

    public function testPartialStockRefusesOrder(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 5));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 10);
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertFalse($result->succesExecution());
    }

    public function testAcceptsOrderWithExactStock(): void
    {
        $service = new ServiceCommande();
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        
        $stock = new Stock();
        $stock->ajouter('vanille', 1);
        $caisse = new Caisse();
        
        $result = $service->traiter($commande, $stock, $caisse);
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals(0, $stock->quantiteDe('vanille'));
    }
}
