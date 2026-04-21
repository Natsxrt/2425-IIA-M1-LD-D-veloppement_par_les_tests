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

    public function testMultipleInstancesAreIndependent(): void
    {
        $result1 = ResultatCommande::succes('Success 1');
        $result2 = ResultatCommande::refus('Refusal 2');
        
        $this->assertTrue($result1->succesExecution());
        $this->assertFalse($result2->succesExecution());
        $this->assertEquals('Success 1', $result1->message());
        $this->assertEquals('Refusal 2', $result2->message());
    }
}
