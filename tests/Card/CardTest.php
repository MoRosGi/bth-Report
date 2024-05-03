<?php

namespace App\Card;

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
     * Construct object Card with setUp.
     */
    protected function setUp(): void
    {
        $this->card = new Card('Hearts', '7');
    }

    /**
     * Verify that the constructed object is an instance of Card class.
     */
    public function testCreateCard(): void
    {
        $this->assertInstanceOf("\App\Card\Card", $this->card);
    }

    /**
     * Verify that the object has the expected suit property.
     */
    public function testCardSuit(): void
    {
        $res = $this->card->getSuit();
        $exp = 'Hearts';

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected string value.
     */
    public function testCardString(): void
    {
        $res = $this->card->cardString();
        $exp = '7 of Hearts';

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected letter string value.
     */
    public function testCardStringLetter(): void
    {
        $res = $this->card->cardStringLetter();
        $exp = '[' . '7' . ' ' . 'Hearts' . ']';

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected integer value.
     */
    public function testCardValue(): void
    {
        $res = $this->card->cardValue($this->card);
        $exp = 7;

        $this->assertEquals($exp, $res);
    }
}
