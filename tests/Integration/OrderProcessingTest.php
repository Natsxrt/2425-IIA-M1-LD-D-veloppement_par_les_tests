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
}
