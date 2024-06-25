<?php

namespace App\Project;

use App\Project\Hand;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Hand.
 */
class HandTest extends TestCase
{
    /**
     * @var Hand $hand
     */
    private $hand;

    /**
     * Set up a new hand object before each test.
     */
    protected function setUp(): void
    {
        $this->hand = new Hand();
        $this->hand->addCard(new Card("Hearts", "8"));
        $this->hand->addCard(new Card("Clubs", "Ace"));
    }

    /**
     * Test that the method adjust hand value when an Ace is added.
     */
    public function testHandValue(): void
    {
        $resBefore = $this->hand->handValue();
        $this->assertEquals(19, $resBefore);

        $this->hand->addCard(new Card("Clubs", "3"));
        $resAfter = $this->hand->handValue();
        $this->assertEquals(12, $resAfter);
    }

    /**
     * Test that the method return an array of string.
     */
    public function testToString(): void
    {
        $res = ["8 of Hearts", "Ace of Clubs"];
        $this->assertEquals($res, $this->hand->toString());
    }
}
