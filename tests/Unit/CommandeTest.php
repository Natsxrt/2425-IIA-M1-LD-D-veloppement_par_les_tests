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
}
