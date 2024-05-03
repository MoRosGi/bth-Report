<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Player.
 */
class PlayerTest extends TestCase
{
    /**
    * @var Player $player
    */
    private $player;

    /**
     * Construct object Player with setUp.
     */
    protected function setUp(): void
    {
        $this->player = new Player();
    }

    /**
     * Verify that the constructed object is an instance of Player class and
     * has the expected properties.
     */
    public function testCreatePlayer(): void
    {
        $this->assertInstanceOf("\App\Card\Player", $this->player);

        $resName = $this->player->getName();
        $resHand = $this->player->getPlayerHand();

        $this->assertEmpty($resName);
        $this->assertInstanceOf(CardHand::class, $resHand);
    }

    /**
     * Verify that the method add the name of the player.
     */
    public function testPlayerName(): void
    {
        $exp = 'NameTest';

        $this->player->addName($exp);
        $res = $this->player->getName();

        $this->assertEquals($exp, $res);
    }

    /**
     * Verify that the method create and return player board.
     */
    public function testPlayerBoard(): void
    {
        $this->player->addName('Mochi');

        $res = $this->player->getPlayerBoard();

        $exp = $res['name'] === 'Mochi' && array_key_exists('hand', $res) && array_key_exists('handLetter', $res) && array_key_exists('value', $res);

        $this->assertTrue($exp);
    }
}
