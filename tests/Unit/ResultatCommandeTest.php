<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\ResultatCommande;
use PHPUnit\Framework\TestCase;

final class ResultatCommandeTest extends TestCase
{
    public function testCreateSuccessResult(): void
    {
        $result = ResultatCommande::succes('Order successful');
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals('Order successful', $result->message());
    }
}
