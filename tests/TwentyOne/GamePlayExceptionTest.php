<?php

namespace App\TwentyOne;

use PHPUnit\Framework\TestCase;
use App\Card\CardDeck;

/**
 * Test exception case for class GamePlay.
 */
class GamePlayExceptionTest extends TestCase
{
    /**
    * @var GamePlay $gamePlay
    */
    private $gamePlay;

    /**
     * Construct object GamePlay with setUp.
     */
    protected function setUp(): void
    {
        $this->gamePlay = new GamePlay(new CardDeck());
        $this->gamePlay->addPlayers(3);
    }

    /**
     * Verify that the method throw an exception when player name is not found.
     */
    public function testGamePlayException(): void
    {
        $namePlayer = 'Martine';

        $this->expectException(\Exception::class);
        $res = $this->gamePlay->getPlayerBoardByName($namePlayer);

    }
}
