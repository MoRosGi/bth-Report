<?php

namespace App\Card;

use App\Card\CardHand;

/**
 * Class representing a player for card and twenty-one games.
 *
 * Encapsulate main methods to get players's informations.
 *
 * @property string $name The name of the player.
 * @property CardHand $cardHand The hand of the player.
 */
class Player
{
    private string $name;
    private CardHand $cardHand;

    /**
     * Constructor for Player class.
     *
     * Set $name to empty string.
     * Set $cardHand as a new empty CardHand object.
     */
    public function __construct()
    {
        $this->name = '';
        $this->cardHand = new CardHand();
    }

    /**
     * Set a name to a player.
     *
     * @return void
     */
    public function addName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Return player name string.
     *
     * @return string $name The name of the player.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return hand object from player.
     *
     * @return CardHand $cardHand
     */
    public function getPlayerHand(): CardHand
    {
        return $this->cardHand;
    }

    /**
     * Return the player's board.
     *
     * Add name, hand and hand value in an array and return it.
     *
     * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int} $playerBoard
     */
    public function getPlayerBoard(): array
    {
        $playerBoard = [
            'name' => $this->name,
            'hand' => $this->cardHand->handString(),
            'handLetter' => $this->cardHand->handStringLetter(),
            'value' => $this->cardHand->handValue()
            ];

        return $playerBoard;
    }
}
