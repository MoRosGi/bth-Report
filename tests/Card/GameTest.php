<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
    * @var Game $game
    */
    private $game;

    /**
     * Construct object Game with setUp.
     */
    protected function setUp(): void
    {
        $this->game = new Game(new CardDeck());
    }

    /**
     * Verify that the constructed object is an instance of Game class and
     * has the expected properties.
     */
    public function testCreateGame(): void
    {
        $this->assertInstanceOf("\App\Card\Game", $this->game);

        $resDeck = $this->game->getDeck();
        $this->assertNotEmpty($resDeck);
    }

    /**
     * Verify that the method add the number of player given.
     */
    public function testGameAddPlayers(): void
    {
        $this->game->addPlayers(3);
        $res = $this->game->getPlayers();
        $exp = 3;

        $this->assertContainsOnlyInstancesOf(Player::class, $res);
        $this->assertCount($exp, $res);
    }

    /**
     * Verify that the method return all players board informations.
     */
    public function testGamePlayersGame(): void
    {
        $this->game->addPlayers(3);
        $res = $this->game->getPlayersGame();

        $this->assertCount(3, $res);

        foreach ($res as $playersGame) {
            $this->assertArrayHasKey('name', $playersGame);
            $this->assertArrayHasKey('handLetter', $playersGame);
            $this->assertArrayHasKey('hand', $playersGame);
            $this->assertArrayHasKey('value', $playersGame);
        }
    }

    /**
     * Verify that the method draw the given number of card to each player hand and return it.
     */
    public function testGameDealAll(): void
    {
        $numPlayer = 2;
        $numCardEach = 3;

        $this->game->addPlayers($numPlayer);
        $resDeal = $this->game->dealAllPlayers($numCardEach);

        $expDeal = $numPlayer * $numCardEach;
        $this->assertCount($expDeal, $resDeal);
        $this->assertContainsOnlyInstancesOf(Card::class, $resDeal);

        $resGame = $this->game->getPlayersGame();

        foreach ($resGame as $playersGame) {
            $this->assertCount($numCardEach, $playersGame['hand']);
        }
    }
}
