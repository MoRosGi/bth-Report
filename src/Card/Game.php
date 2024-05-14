<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\Player;

/**
 * Base class for a card game.
 *
 * Encapsulate main methods for playing a card game with several players.
 *
 * @property array $players All players in the game.
 * @property CardDeck $deck The deck used in the game.
 */
class Game
{
    /**
    * @var array<Player> $players
    */
    protected array $players;

    /**
    * @var CardDeck $deck
    */
    protected CardDeck $deck;

    /**
     * Constructor for Game class.
     *
     * Set $deck as a CardDeck object.
     *
     * @param \App\Card\CardDeck $deck Deck of cards object.
     */
    public function __construct(CardDeck $deck)
    {
        $this->deck = $deck;
    }

    /**
     * Add a given number of players in the game.
     *
     * Initiate and add Player object in $player array.
     * Use Player class methods to add a generic name for each player.
     *
     * @param int $numPlayer Number of players in the game
     *
     * @return void
     */
    public function addPlayers(int $numPlayer): void
    {
        $this->players = [];

        for ($i = 1; $i <= $numPlayer; $i++) {
            $player = new Player();
            $player->addName("Player " . $i);
            $this->players[] = $player;
        }
    }

    /**
     * Return $deck object.
     *
     * @return \App\Card\CardDeck $deck
     */
    public function getDeck(): object
    {
        return $this->deck;
    }

    /**
     * Return $player array.
     *
     * @return array<Player> $players
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * Set a given number of cards into each player's hand.
     *
     * Use Player class methods to access hand property.
     * Use Deck class methods to draw cards from current deck.
     *
     * @param int $numCards Number of cards to deal to players.
     *
     * @return array<Card> $cardDraw The array of Card objects draw.
     */
    public function dealAllPlayers(int $numCards): array
    {
        $cardDraw = [];
        foreach ($this->players as $player) {
            $hand = $player->getPlayerHand();
            $cards = $this->deck->drawCard($numCards);

            foreach ($cards as $card) {
                $hand->addCard($card);
                array_push($cardDraw, $card);
            }
        }

        return $cardDraw;
    }


    /**
     * Return all players' board.
     *
     * Use Player class methods to get players' name, hand and value.
     *
     * @return array<int<0, max>, array{name: string, hand: array<string>, handLetter: array<string>, value: int}> $playersGame
     */
    public function getPlayersGame(): array
    {
        $playersGame = [];
        foreach ($this->players as $player) {
            $playersGame[] = $player->getPlayerBoard();
        }

        return $playersGame;
    }
}
