<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardDeck.
 */
class CardDeckTest extends TestCase
{
    /**
    * @var CardDeck $cardDeck
    */
    private $cardDeck;

    /**
     * Construct object CardDeck with setUp.
     */
    protected function setUp(): void
    {
        $this->cardDeck = new CardDeck();
    }

    /**
     * Verify that the constructed object is an instance of CardDeck class and
     * has the expected properties.
     */
    public function testCreateCardDeck(): void
    {
        $this->assertInstanceOf("\App\Card\CardDeck", $this->cardDeck);

        $res = $this->cardDeck->getDeck();
        $this->assertContainsOnlyInstancesOf(Card::class, $res);
    }

    /**
     * Verify that the method shuffle the deck property.
     */
    public function testCardDeckShuffle(): void
    {
        $res = $this->cardDeck->getDeck();
        $this->cardDeck->shuffleDeck();
        $resShuffle = $this->cardDeck->getDeck();

        $this->assertNotEquals($res, $resShuffle);
    }

    /**
     * Verify that the method reset the deck property.
     */
    public function testCardDeckReset(): void
    {
        $res = $this->cardDeck->getDeck();
        $this->cardDeck->shuffleDeck();
        $this->cardDeck->resetDeck();
        $resReset = $this->cardDeck->getDeck();

        $this->assertEquals($res, $resReset);
    }

    /**
     * Verify that the method return the expected number.
     */
    public function testCardDeckCount(): void
    {
        $res = $this->cardDeck->countDeck();
        $exp = 52;

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected array of strings.
     */
    public function testCardDeckString(): void
    {
        $res = $this->cardDeck->getDeck();
        $resString = $this->cardDeck->toString($res);

        $this->assertContainsOnly('string', $resString);
    }

    /**
     * Verify that the method draw given number of cards and return them.
     */
    public function testCardDeckDraw(): void
    {
        $resDraw = $this->cardDeck->drawCard(3);
        $res = $this->cardDeck->getDeck();

        $expDraw = 3;
        $expRes = 49;

        $this->assertCount($expDraw, $resDraw);
        $this->assertCount($expRes, $res);
    }

    /**
     * Verify that the method remove given card object from deck property.
     */
    public function testCardDeckRemove(): void
    {
        $this->cardDeck->removeFromDeck([new Card('Hearts', '7')]);
        $res = $this->cardDeck->getDeck();

        $this->assertNotContains(new Card('Hearts', '7'), $res);
    }
}
