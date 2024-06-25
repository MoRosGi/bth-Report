<?php

namespace App\Project;

use App\Project\Dealer;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dealer.
 */
class DealerTest extends TestCase
{
    /**
     * @var Dealer $dealer
     */
    private $dealer;

    /**
     * Set up a new Dealer object before each test.
     */
    protected function setUp(): void
    {
        $this->dealer = new Dealer();
        $this->dealer->getHand()->addCard((new Card("Diamonds", "5")));
    }

    /**
     * Test that the method reset the dealers hand to em empty object.
     */
    public function testReset(): void
    {
        $this->assertCount(1, $this->dealer->getHand()->getCards());
        $this->dealer->resetHand();
        $this->assertCount(0, $this->dealer->getHand()->getCards());
    }
}
