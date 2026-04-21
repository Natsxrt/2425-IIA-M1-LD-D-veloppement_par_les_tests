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
}
