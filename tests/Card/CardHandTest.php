<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
    * @var Card $cardOne
    */
    private $cardOne;

    /**
    * @var Card $cardTwo
    */
    private $cardTwo;

    /**
    * @var CardHand $cardHand
    */
    private $cardHand;

    /**
     * Construct object CardHand with setUp.
     * Add instance of Card object.
     */
    protected function setUp(): void
    {
        $this->cardOne = new Card('Hearts', '7');
        $this->cardTwo = new Card('Diamonds', 'Queen');
        $this->cardHand = new CardHand();

        $this->cardHand->addCard($this->cardOne);
        $this->cardHand->addCard($this->cardTwo);
    }

    /**
     * Verify that the object is an instance of CardHand class.
     * Verify that the object hand property is not empty after setUp.
     */
    public function testCreateCardHand(): void
    {
        $this->assertInstanceOf("\App\Card\CardHand", $this->cardHand);

        $res = $this->cardHand->getHand();
        $this->assertNotEmpty($res);
    }

    /**
     * Verify that the method return the expected array of strings.
     */
    public function testCardHandString(): void
    {
        $res = $this->cardHand->handString();
        $exp = ['7 of Hearts', 'Queen of Diamonds'];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected array of letter strings.
     */
    public function testCardHandStringLetter(): void
    {
        $res = $this->cardHand->handStringLetter();
        $exp = ['[' . '7' . ' ' . 'Hearts' . ']', '[' . 'Queen' . ' ' . 'Diamonds' . ']'];

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected hand value.
     */
    public function testCardHandValue(): void
    {
        $res = $this->cardHand->handValue();
        $exp = 19;

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method adjust and return the expected hand value when an Ace is added in hand.
     */
    public function testCardHandValueAce(): void
    {
        $cardThree = new Card('Spades', 'Ace');
        $this->cardHand->addCard($cardThree);

        $res = $this->cardHand->handValue();
        $exp = 20;

        $this->assertEquals($exp, $res);
    }
}
