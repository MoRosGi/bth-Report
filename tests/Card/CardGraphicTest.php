<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
    * @var CardGraphic $cardGraphic
    */
    private $cardGraphic;

    /**
     * Construct object CardGraphic with setUp.
     */
    protected function setUp(): void
    {
        $this->cardGraphic = new CardGraphic('Hearts', '7');
    }

    /**
     * Verify that the constructed object is an instance of CardGraphic class.
     */
    public function testCreateCardGraphic(): void
    {
        $this->assertInstanceOf("\App\Card\CardGraphic", $this->cardGraphic);
    }

    /**
     * Verify that the method return the expected string value.
     */
    public function testCardGraphicString(): void
    {
        $res = $this->cardGraphic->cardString();
        $exp = '[' . '7' . '♥' . ']';

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method return the expected letter string value.
     */
    public function testCardGraphicStringLetter(): void
    {
        $res = $this->cardGraphic->cardStringLetter();
        $exp = '[' . '7' . ' ' . 'Hearts' . ']';

        $this->assertEquals($exp, $res);
    }
}
