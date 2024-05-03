<?php

namespace App\TwentyOne;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\Game;
use App\Card\Player;

/**
 * Main class for Twenty-One game.
 *
 * Encapsulate main methods for playing Twenty-One game by extending Game class.
 *
 * @property Player $dealer The dealer for the game.
 */
class GamePlay extends Game
{
    /**
    * @var Player $dealer
    */
    private Player $dealer;


    /**
     * Constructor for GamePlay class.
     *
     * Set $dealer as a Player object. Set a name to dealer.
     *
     * @param \App\Card\CardDeck $deck Deck of cards object.
     */
    public function __construct(CardDeck $deck)
    {
        parent::__construct($deck);

        $this->dealer = new Player();
        $this->dealer->addName("Dealer");
    }

    /**
     * Return dealer object.
     *
     * @return Player $dealer
     */
    public function getDealer(): object
    {
        return $this->dealer;
    }

    /**
     * Set a given number of cards into a given player(name)'s hand.
     *
     * Use Player class methods to access name and hand property.
     * Use Deck class methods to draw cards from current deck.
     *
     * @param int $numCards Number of cards to deal to player.
     * @param string $name Name of the player to deal cards to.
     *
     * @return void
     */
    public function dealOnePayer(int $numCards, string $name): void
    {
        foreach ($this->players as $player) {
            if ($player->getName() === $name) {
                $hand = $player->getPlayerHand();
                $cards = $this->deck->drawCard($numCards);

                foreach ($cards as $card) {
                    $hand->addCard($card);
                }
            }
        }
    }

    /**
     * Set a given number of cards into dealer's hand.
     *
     * Use Player class methods to access hand property.
     * Use Deck class methods to draw cards from current deck.
     *
     * @param int $numCards Number of cards to deal to dealer.
     *
     * @return void
     */
    public function dealDealer(int $numCards): void
    {
        $hand = $this->dealer->getPlayerHand();
        $cards = $this->deck->drawCard($numCards);

        foreach ($cards as $card) {
            $hand->addCard($card);
        }
    }


    /**
     * Return dealers's board.
     *
     * Use Player class methods to get player's name, hand and value.
     *
     * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int}
     */
    public function getDealerGame(): array
    {
        return $this->dealer->getPlayerBoard();
    }


    /**
     * Return player's board with a given name.
     *
     * Use Player class methods to get player's name, hand and value.
     *
     * @param string $name The name of the player.
     *
     * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int}
     *
     * @throws \Exception When player with the specified name is not found
     */
    public function getPlayerBoardByName(string $name): array
    {
        foreach ($this->players as $player) {
            if ($player->getName() === $name) {
                return $player->getPlayerBoard();
            }
        }

        throw new \Exception("Player '$name' not found.");
    }

    /**
     * Return a specific message depending of the winner.
     *
     * Evaluate who from dealer and player got a hand value of 21, above 21 or highest hand value.
     *
     * @param int $dealerValue The dealer's hand value.
     * @param int $playerValue The player's hand value.
     *
     * @return string $message
     */
    public function endGame(int $dealerValue, int $playerValue): string
    {
        $message = '';

        if ($playerValue > 21 || $dealerValue > $playerValue && $dealerValue < 21 || $dealerValue === $playerValue || $dealerValue == 21) {
            $message = 'Dealer won the game!';
        }
        if ($dealerValue > 21 || $playerValue > $dealerValue && $playerValue < 21 || $playerValue == 21) {
            $message = 'Congrats! You won the game!';
        }

        return $message;
    }

}
