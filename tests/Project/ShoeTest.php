<?php

namespace App\Project;

use App\Project\Shoe;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Shoe.
 */
class ShoeTest extends TestCase
{
    /**
     * @var Shoe $shoe
     */
    private $shoe;

    /**
     * @var int $numOfDeck
     */
    private $numOfDeck;


    /**
     * Set up a new Shoe object before each test.
     */
    protected function setUp(): void
    {
        $this->numOfDeck = 2;
        $this->shoe = new Shoe($this->numOfDeck);
    }

    /**
     * Test the creation of the Shoe object.
     */
    public function testCreateShoe(): void
    {
        $this->assertInstanceOf(Shoe::class, $this->shoe);
    }

    /**
     * Test that the Shoe object contains the correct number of Decks and cards.
     */
    public function testCountShoe(): void
    {
        $exp = $this->numOfDeck * 52;
        $res = $this->shoe->countCards();

        $this->assertEquals($exp, $res);
    }

    /**
     * Test dealing a card from the Shoe.
     */
    public function testDealCard(): void
    {
        $card = $this->shoe->dealCard();

        $exp = ($this->numOfDeck * 52) - 1;
        $res = $this->shoe->countCards();

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals($exp, $res);
    }

    /**
     * Test that the method return the string representation of the shoe.
     */
    public function testToString(): void
    {
        $shoeString = $this->shoe->toString();
        $expCount = ($this->numOfDeck * 52);

        $this->assertContainsOnly('string', $shoeString);
        $this->assertCount($expCount, $shoeString);
    }
}
