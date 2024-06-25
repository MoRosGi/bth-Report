<?php

namespace App\Project;

use App\Project\Player;

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
     * Set up a new Player object before each test.
     */
    protected function setUp(): void
    {
        $this->player = new Player();

        $this->player->addHand(new Hand());
        $this->player->addHand(new Hand());
    }

    /**
     * Test that the method reset the players hand to em empty array.
     */
    public function testReset(): void
    {
        $this->assertCount(2, $this->player->getHands());
        $this->player->resetHand();
        $this->assertCount(0, $this->player->getHands());
    }

    /**
     * Test that the method set the name to the player name attribut.
     */
    public function testName(): void
    {
        $this->player->setName("Charlie");
        $this->assertEquals("Charlie", $this->player->getName());
    }
}
