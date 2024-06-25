<?php

namespace App\Project;

use App\Project\Game;
use App\Project\Hand;
use App\Project\Card;

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
     * Set up a new Game object before each test.
     */
    protected function setUp(): void
    {
        $this->game = new Game();
    }

    /**
     * Test the creation of the Game object and its initial properties.
     */
    public function testCreateGame(): void
    {
        $expActiveHand = 0;
        $expGameState = "init";

        $this->assertInstanceOf(Game::class, $this->game);
        $this->assertInstanceOf(Player::class, $this->game->getPlayer());
        $this->assertInstanceOf(Dealer::class, $this->game->getDealer());
        $this->assertInstanceOf(Shoe::class, $this->game->getShoe());
        $this->assertEquals($expActiveHand, $this->game->getActiveHandIndex());
        $this->assertEquals($expGameState, $this->game->getGameState());
    }

    /**
     * Test adding hands to the player.
     */
    public function testAddHand(): void
    {
        $numOfHand = 2;
        $sumToAdd = 155;

        $this->game->getPlayer()->adjustBalance(-$sumToAdd);
        $res = $this->game->addHands($numOfHand);
        $this->assertFalse($res, "Not enough balance, should return false");

        $this->game->getPlayer()->adjustBalance($sumToAdd);
        $this->game->addHands($numOfHand);
        $playerHands = $this->game->getPlayer()->getHands();

        $this->assertCount($numOfHand, $playerHands, "Success, should add the correct amount of Hands.");
        $this->assertContainsOnlyInstancesOf(Hand::class, $playerHands);
    }

    /**
     * Test distributing bets to hands.
     */
    public function testDistributesBets(): void
    {
        $numOfHand = 2;
        $sumBetOne = 165;
        $betsOne = [50, 65, 50];
        $sumBetTwo = 10;
        $betsTwo = [4, 6];

        $this->game->addHands($numOfHand);
        $res = $this->game->distributeBets($sumBetOne, $betsOne);
        $this->assertFalse($res, "Bets too hight, should return false");

        $this->game->distributeBets($sumBetTwo, $betsTwo);
        $playerHands = $this->game->getPlayer()->getHands();
        $this->assertEquals($betsTwo[0], $playerHands[0]->getBet());
        $this->assertEquals($betsTwo[1], $playerHands[1]->getBet());
    }

    /**
     * Test dealing cards to player and dealer hands.
     */
    public function testDeal(): void
    {
        $numOfHand = 2;
        $this->game->addHands($numOfHand);
        $this->game->deal();

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $this->assertCount(2, $playerHands[0]->getCards());
        $this->assertCount(2, $playerHands[1]->getCards());
        $this->assertCount(2, $dealerHand->getCards());
    }

    /**
     * Test setting the active hand index correctly.
     */
    public function testSetTurn(): void
    {
        $cardOne = new Card("Clubs", "Ace");
        $cardTwo = new Card("Hearts", "10");
        $cardThree = new Card("Diamonds", "5");
        $cardFour = new Card("Hearts", "10");
        $cardFive = new Card("Clubs", "6");

        $this->game->getPlayer()->addHand(new Hand());
        $this->game->getPlayer()->addHand(new Hand());

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $playerHands[0]->addCard($cardOne);
        $playerHands[0]->addCard($cardTwo);
        $playerHands[1]->addCard($cardThree);
        $playerHands[1]->addCard($cardFour);

        $dealerHand->addCard($cardFour);
        $dealerHand->addCard($cardFive);

        $startIndex = $this->game->getActiveHandIndex();

        $this->game->setTurn($startIndex);
        $indexFirstTurn = $this->game->getActiveHandIndex();

        $playerHands[1]->addCard($cardFive);

        $this->game->setTurn($indexFirstTurn);
        $indexSecondTurn = $this->game->getActiveHandIndex();

        $this->assertEquals($startIndex + 1, $indexFirstTurn, "The turn should switch to the next hand");
        $this->assertEquals($startIndex, $indexSecondTurn, "The turn should cycle back to the first hand");
    }

    /**
     * Test player actions "hit" and "stand".
     */
    public function testPlayerActions(): void
    {
        $this->game->getPlayer()->addHand(new Hand());
        $playerHands = $this->game->getPlayer()->getHands();

        $countBefore = count($playerHands[0]->getCards());
        $indexBefore = $this->game->getActiveHandIndex();

        $this->game->playerAction("hit");
        $this->game->playerAction("stand");

        $countAfter = count($playerHands);
        $indexAfter = $this->game->getActiveHandIndex();

        $this->assertEquals($countBefore + 1, $countAfter, "The hand should have one more card after hit");
        $this->assertEquals($indexBefore + 1, $indexAfter, "The turn should switch to the next hand after stand");
    }

    /**
     * Test the correct win value is placed when player has blackjack.
     */
    public function testCountWinsBlackjack(): void
    {
        $bet = 2;

        $cardOne = new Card("Clubs", "Ace");
        $cardTwo = new Card("Hearts", "10");
        $cardThree = new Card("Diamonds", "5");
        $cardFour = new Card("Hearts", "10");

        $this->game->getPlayer()->addHand(new Hand());

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $dealerHand->addCard($cardThree);
        $dealerHand->addCard($cardFour);

        $playerHands[0]->addCard($cardOne);
        $playerHands[0]->addCard($cardTwo);
        $playerHands[0]->placeBet($bet);

        $this->game->setEventualBlackjack();
        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * 1.5);

        $this->assertTrue($playerHands[0]->isBlackjack());
        $this->assertFalse($dealerHand->isBlackjack());
        $this->assertEquals($exp, $res, "Player has blackjack, should get multiplier 1.5 for its bet.");
    }

    /**
     * Test the correct win value is placed when player looses.
     */
    public function testCountWinsLoose(): void
    {
        $bet = 2;

        $cardOne = new Card("Clubs", "10");
        $cardTwo = new Card("Spades", "3");
        $cardThree = new Card("Diamonds", "10");
        $cardFour = new Card("Hearts", "Ace");

        $this->game->getPlayer()->addHand(new Hand());

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $dealerHand->addCard($cardThree);
        $dealerHand->addCard($cardFour);

        $playerHands[0]->addCard($cardOne);
        $playerHands[0]->addCard($cardTwo);
        $playerHands[0]->placeBet($bet);

        $this->game->setEventualBlackjack();
        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * -1);
        $this->assertEquals($exp, $res, "Dealer has blackjack, Player looses, should get multiplier -1 for its bet.");

        $playerHands[0]->addCard($cardThree);
        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * -1);
        $this->assertEquals($exp, $res, "Player bust, should get multiplier -1 for its bet.");
    }

    /**
     * Test the correct win value is placed when push.
     */
    public function testCountWinsPush(): void
    {
        $bet = 2;

        $cardOne = new Card("Clubs", "6");
        $cardTwo = new Card("Spades", "3");
        $cardThree = new Card("Clubs", "6");
        $cardFour = new Card("Spades", "3");

        $this->game->getPlayer()->addHand(new Hand());

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $dealerHand->addCard($cardThree);
        $dealerHand->addCard($cardFour);

        $playerHands[0]->addCard($cardOne);
        $playerHands[0]->addCard($cardTwo);
        $playerHands[0]->placeBet($bet);

        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * 0);
        $this->assertEquals($exp, $res, "Push, should get multiplier 0 for its bet.");
    }

    /**
     * Test the correct win value is placed when player wins.
     */
    public function testCountWinsWin(): void
    {
        $bet = 2;

        $cardOne = new Card("Clubs", "10");
        $cardTwo = new Card("Spades", "7");
        $cardThree = new Card("Clubs", "5");
        $cardFour = new Card("Spades", "10");

        $this->game->getPlayer()->addHand(new Hand());

        $playerHands = $this->game->getPlayer()->getHands();
        $dealerHand = $this->game->getDealer()->getHand();

        $dealerHand->addCard($cardThree);
        $dealerHand->addCard($cardFour);

        $playerHands[0]->addCard($cardOne);
        $playerHands[0]->addCard($cardTwo);
        $playerHands[0]->placeBet($bet);

        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * 1);
        $this->assertEquals($exp, $res, "Player wins, should get multiplier 1 for its bet.");

        $dealerHand->addCard($cardTwo);

        $this->game->countWins();

        $res = $playerHands[0]->getWinning();
        $exp = $bet + ($bet * 1);
        $this->assertEquals($exp, $res, "Dealer bust, should get multiplier 1 for its bet.");
    }
}
