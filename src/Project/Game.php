<?php

namespace App\Project;

use App\Project\Player;
use App\Project\Dealer;
use App\Project\Shoe;
use App\Project\Hand;

/**
 * Class representing a Game.
 * The main class for the blackjack game.
 *
 * Encapsulate methods related to a game object.
 *
 * @property Player $player The player in the game.
 * @property Dealer $dealer The dealer in the game.
 * @property Shoe $shoe The shoe used in the game.
 * @property int $activeHandIndex The index of the active hand of the player.
 * @property string $gameState The state of the game.
 */
class Game
{
    private const NUM_OF_DECKS = 3;
    private const MIN_BET = 1;
    private const PLAYER_VALUE_LIMIT = 21;
    private const DEALER_VALUE_LIMIT = 17;
    private Player $player;
    private Dealer $dealer;
    private Shoe $shoe;
    private int $activeHandIndex;
    private string $gameState;

    /**
     * Constructor for Game class.
     *
     * Assign $player to a new player object.
     * Assign $dealer to a new dealer object.
     * Assign $shoe to a new shoe object with the constant NUM_OF_DECKS.
     * Assign $activeHandIndex to 0.
     * Assign $gameState to the initial "init" state.
     */
    public function __construct()
    {
        $this->player = new Player();
        $this->dealer = new Dealer();
        $this->shoe = new Shoe(self::NUM_OF_DECKS);
        $this->activeHandIndex = 0;
        $this->gameState = "init";
    }

    /**
     * Get player object.
     * @return Player The player in the game.
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get dealer object.
     * @return Dealer The dealer in the game.
     */
    public function getDealer(): Dealer
    {
        return $this->dealer;
    }

    /**
     * Get shoe object.
     * @return Shoe The shoe in the game.
     */
    public function getShoe(): Shoe
    {
        return $this->shoe;
    }

    /**
     * Get the active hand of the player, the hand to play.
     * @return int The index of the active hand.
     */
    public function getActiveHandIndex(): int
    {
        return $this->activeHandIndex;
    }

    /**
     * Get the state of the game.
     * @return string The state of the game.
     */
    public function getGameState(): string
    {
        return $this->gameState;
    }

    /**
     * Add the number of hands request by the player in UI. Use the addHand method from the Player class.
     * Control that the player has enough balance to bet the minimum value for each hand.
     * Set the new state of the game.
     * Return true if the conditions are met, otherwise return false.
     * @param int $numOfHand The number of hand to add.
     * @return bool
     */
    public function addHands(int $numOfHand): bool
    {
        if ($this->player->getBalance() / $numOfHand >= self::MIN_BET) {
            for ($i = 0; $i < $numOfHand; $i++) {
                $this->player->addHand(new Hand());
            }
            $this->gameState = "round start";
            return true;
        }
        return false;
    }

    /**
     * Distributes the bets set by the player in UI for each hands.
     * Control that the player has enough balance for the total bet.
     * Set the new state of the game.
     * Return true if the conditions are met, otherwise return false.
     * @param int $sumBet The sum of the bets.
     * @param array<int> $bets The array of bets from the UI.
     * @return bool
     */
    public function distributeBets(int $sumBet, array $bets): bool
    {
        $handsCount = count($this->player->getHands());
        if ($this->player->getBalance() >= $sumBet) {
            for ($i = 0; $i < $handsCount; $i++) {
                $this->player->getHands()[$i]->placeBet($bets[$i]);
            }
            $this->player->adjustBalance(-$sumBet);
            $this->gameState = "deal";
            return true;
        }
        return false;
    }

    /**
     * Distributes the first two cards for each of players hand and dealers hand.
     * For the dealers hand, flip the card to have a face up one and face down one.
     * Set the new state of the game.
     */
    public function deal(): void
    {
        foreach ($this->player->getHands() as $hand) {
            $hand->addCard($this->shoe->dealCard());
            $hand->addCard($this->shoe->dealCard());
        }

        $this->dealer->getHand()->addCard($this->shoe->dealCard());

        $faceDownCard = $this->shoe->dealCard();
        $faceDownCard->flipCard();
        $this->dealer->getHand()->addCard($faceDownCard);
        $this->gameState = "player turn";
    }

    /**
     * Set the eventual blackjack on the players hands and dealers hand.
     * Use the method hasBlackhack from the Hand class.
     */
    public function setEventualBlackjack(): void
    {
        $playerHands = $this->player->getHands();
        $dealerHand = $this->dealer->getHand();
        $handsCount = count($this->player->getHands());

        if ($dealerHand->handValue() === self::PLAYER_VALUE_LIMIT) {
            $dealerHand->hasBlackjack();
        }

        for ($i = 0; $i < $handsCount; $i++) {
            if ($playerHands[$i]->handValue() === self::PLAYER_VALUE_LIMIT) {
                $playerHands[$i]->hasBlackjack();
            }
        }
    }

    /**
     * Set the turn in the game with the active hand.
     * Control if the active hand is the last hand of the player, change the games state and call the method dealerAction.
     * Control if the active hand can keep playing.
     * Control if the active hand need to be changed to the next hand.
     * @param int $handIndex The players hand that needs to be checked.
     */
    public function setTurn(int $handIndex): void
    {
        $playerHands = $this->player->getHands();
        $handsCount = count($this->player->getHands());
        $control = true;
        $handIndex = $this->activeHandIndex;

        while ($control) {
            if ($handIndex === $handsCount) {
                $this->gameState = "dealer turn";
                $this->dealerAction();
                $handIndex = 0;
                break;
            }
            if ($playerHands[$handIndex]->handValue() < self::PLAYER_VALUE_LIMIT) {
                $control = false;
            }
            if ($playerHands[$handIndex]->handValue() >= self::PLAYER_VALUE_LIMIT) {
                $handIndex += 1;
            }
        }
        $this->activeHandIndex = $handIndex;
    }

    /**
     * Decides which action to execute for the active hand from the UI request.
     * If action is hit add a new card to the active hand player.
     * If action is stand increment the active hand index for playing the next hand.
     * @param string $action The action chose on UI.
     */
    public function playerAction(string $action): void
    {
        $playerHands = $this->player->getHands();
        $playerHand = $playerHands[$this->activeHandIndex];

        if ($action === 'hit') {
            $playerHand->addCard($this->shoe->dealCard());
        }
        if ($action === 'stand') {
            $this->activeHandIndex += 1;
        }
    }

    /**
     * Executes when turn goes to the dealer.
     * Flip the face down card to show the full dealer hand.
     * Add a new card to the dealers hand until the value reach the maximum allowed.
     * Assign the new games state.
     */
    public function dealerAction(): void
    {
        $dealerHand = $this->dealer->getHand();
        $dealerHand->getCards()[1]->flipCard();

        while ($dealerHand->handValue() <= self::DEALER_VALUE_LIMIT) {
            $this->dealer->getHand()->addCard($this->shoe->dealCard());
        }
        $this->gameState = "count win";
    }

    /**
     * Calculate the win for each of the payers hand.
     * Use the method calculateMultiplier to get the multiplier to use.
     * Set the formula to calculate the win.
     * Use the method placeWinning from the Hand class for each hand.
     * Adjust the balance of the player.
     */
    public function countWins(): void
    {
        foreach ($this->player->getHands() as $hand) {
            $multiplier = $this->calculateMultiplier($hand);
            $bet = $hand->getBet();
            $winning = $bet + ($bet * $multiplier);

            $hand->placeWinning($winning);
            $this->player->adjustBalance($winning);
        }
        $this->gameState = "round end";
    }

    /**
     * Private method helper to calculate the multiplier to use for win calculation.
     * Use the method handValue from the Hand class to get the value of the hand.
     * Use the method getIsBlackjack from the Hand class to control if the hand has blackjack.
     * @param Hand $hand the hand to evaluate.
     */
    private function calculateMultiplier(Hand $hand): float
    {
        $dealerHand = $this->dealer->getHand();
        if ($hand->isBlackjack() && $dealerHand->isBlackjack() === false) {
            return 1.5;
        }
        if ($hand->handValue() > self::PLAYER_VALUE_LIMIT) {
            return -1;
        }
        if ($dealerHand->handValue() > self::PLAYER_VALUE_LIMIT) {
            return 1;
        }
        if ($hand->handValue() > $dealerHand->handValue()) {
            return 1;
        }
        if ($hand->handValue() < $dealerHand->handValue()) {
            return -1;
        }
        return 0;
    }
}
