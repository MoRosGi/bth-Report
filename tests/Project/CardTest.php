<?php

namespace App\Project;

use App\Project\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * @var Card $card
     */
    private $card;

    /**
     * Set up a new card object before each test.
     */
    protected function setUp(): void
    {
        $this->card = new Card("Hearts", "8");
    }

    /**
     * Test that the method return the correct string.
     */
    public function testToString(): void
    {
        $this->assertEquals("8 of Hearts", $this->card->toString());
    }

    /**
     * Test that the method return the correct image path.
     */
    public function testGetImageLink(): void
    {
        $this->assertEquals("hearts_8", $this->card->getImageLink());

        $this->card->flipCard();
        $this->assertEquals("back", $this->card->getImageLink());
    }
}
