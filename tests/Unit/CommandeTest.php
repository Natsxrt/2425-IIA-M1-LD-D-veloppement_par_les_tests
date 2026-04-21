<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Commande;
use App\Glace;
use LogicException;
use PHPUnit\Framework\TestCase;

final class CommandeTest extends TestCase
{
    public function testItComputesTheTotalPrice(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'cornet', 5));

        self::assertSame(9, $commande->prixTotal());
    }

    public function testItCannotBeDeliveredTwice(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->livrer();

        $this->expectException(LogicException::class);

        $commande->livrer();
    }

    public function testItStartsEmpty(): void
    {
        $commande = new Commande();
        $this->assertTrue($commande->estVide());
    }

    public function testItIsNotEmptyAfterAddingGlace(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $this->assertFalse($commande->estVide());
    }

    public function testItCannotBeDeliveredWhenEmpty(): void
    {
        $commande = new Commande();
        $this->expectException(LogicException::class);
        $commande->livrer();
    }

    public function testItIsDeliveredAfterCalling(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $this->assertFalse($commande->estLivree());
        $commande->livrer();
        $this->assertTrue($commande->estLivree());
    }

    public function testCannotAddGlaceAfterDelivery(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 4));
        $commande->livrer();
        
        $this->expectException(LogicException::class);
        $commande->ajouterGlace(new Glace('chocolat', 'chocolat', 'pot', 5));
    }

    public function testComputesTotalPriceWithMultipleGlaces(): void
    {
        $commande = new Commande();
        $commande->ajouterGlace(new Glace('vanille', 'vanille', 'pot', 10));
        $commande->ajouterGlace(new Glace('fraise', 'fraise', 'cornet', 7));
        $commande->ajouterGlace(new Glace('pistache', 'pistache', 'pot', 8));
        $this->assertEquals(25, $commande->prixTotal());
    }

    public function testReturnsGlacesInCorrectOrder(): void
    {
        $commande = new Commande();
        $glace1 = new Glace('vanille', 'vanille', 'pot', 4);
        $glace2 = new Glace('chocolat', 'chocolat', 'cornet', 5);
        $commande->ajouterGlace($glace1);
        $commande->ajouterGlace($glace2);
        
        $glaces = $commande->glaces();
        $this->assertCount(2, $glaces);
        $this->assertSame($glace1, $glaces[0]);
        $this->assertSame($glace2, $glaces[1]);
    }

    public function testEmptyCommandeHasTotalPriceZero(): void
    {
        $commande = new Commande();
        $this->assertEquals(0, $commande->prixTotal());
    }
}
