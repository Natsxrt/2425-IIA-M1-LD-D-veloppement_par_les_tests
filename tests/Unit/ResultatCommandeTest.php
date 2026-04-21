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

    public function testCreateRefusResult(): void
    {
        $result = ResultatCommande::refus('Order refused');
        
        $this->assertFalse($result->succesExecution());
        $this->assertEquals('Order refused', $result->message());
    }

    public function testSuccessMessageCanBeEmpty(): void
    {
        $result = ResultatCommande::succes('');
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals('', $result->message());
    }

    public function testRefusMessageCanBeEmpty(): void
    {
        $result = ResultatCommande::refus('');
        
        $this->assertFalse($result->succesExecution());
        $this->assertEquals('', $result->message());
    }

    public function testSuccessMessageWithLongText(): void
    {
        $longMessage = 'This is a very long message about a successful order processing that contains lots of information';
        $result = ResultatCommande::succes($longMessage);
        
        $this->assertTrue($result->succesExecution());
        $this->assertEquals($longMessage, $result->message());
    }

    public function testRefusMessageWithLongText(): void
    {
        $longMessage = 'This is a very long message about a refused order with detailed reasons for the refusal';
        $result = ResultatCommande::refus($longMessage);
        
        $this->assertFalse($result->succesExecution());
        $this->assertEquals($longMessage, $result->message());
    }
}
