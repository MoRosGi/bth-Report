<?php

namespace App\TwentyOne;

use PHPUnit\Framework\TestCase;
use App\Card\CardDeck;

/**
 * Test cases for class GamePlay.
 */
class GamePlayTest extends TestCase
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
     * Verify that the constructed object is an instance of GamePlay class and
     * has the expected properties.
     */
    public function testCreateGamePlay(): void
    {
        $this->assertInstanceOf("\App\TwentyOne\GamePlay", $this->gamePlay);

        $res = $this->gamePlay->getDealer();
        $this->assertInstanceOf("\App\Card\Player", $res);
    }

    /**
     * Verify that the method return the given name player board informations.
     */
    public function testGamePlayPlayerGame(): void
    {
        $namePlayer = 'Player 1';

        $res = $this->gamePlay->getPlayerBoardByName($namePlayer);

        $this->assertEquals($namePlayer, $res['name']);
    }

    /**
     * Verify that the method draw the given number of card to the given name player.
     */
    public function testGamePlayDealOne(): void
    {
        $numCard = 2;
        $namePlayer = 'Player 1';

        $this->gamePlay->dealOnePayer($numCard, $namePlayer);

        $res = $this->gamePlay->getPlayerBoardByName($namePlayer);

        $this->assertEquals($numCard, count($res['hand']));
    }

    /**
     * Verify that the method draw the given number of card to the dealer.
     */
    public function testGamePlayDealDealer(): void
    {
        $numCard = 3;

        $this->gamePlay->dealDealer($numCard);

        $res = $this->gamePlay->getDealerGame();

        $this->assertEquals($numCard, count($res['hand']));
    }

    /**
     * Verify that the method return the correct message with given hands value.
     */
    public function testGamePlayEnd(): void
    {
        $dealerValueOne = 21;
        $playerValueOne = 18;

        $resOne = $this->gamePlay->endGame($dealerValueOne, $playerValueOne);
        $expOne = 'Dealer won the game!';
        $this->assertEquals($expOne, $resOne);

        $dealerValueTwo = 15;
        $playerValueTwo = 18;

        $resTwo = $this->gamePlay->endGame($dealerValueTwo, $playerValueTwo);
        $expTwo = 'Congrats! You won the game!';
        $this->assertEquals($expTwo, $resTwo);

        $dealerValueThree = 17;
        $playerValueThree = 17;

        $resThree = $this->gamePlay->endGame($dealerValueThree, $playerValueThree);
        $expThree = 'Dealer won the game!';
        $this->assertEquals($expThree, $resThree);
    }
}
