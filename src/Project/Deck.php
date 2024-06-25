<?php

namespace App\Project;

use App\Project\Card;

/**
 * Class representing a deck of cards.
 *
 * Encapsulate methods related to a deck object.
 *
 * @property array $cards Contains all cards in the deck.
 */
class Deck
{
    private const SUITS = ['Spades', 'Hearts', 'Clubs', 'Diamonds'];
    private const VALUES = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

    /**
     * @var array<Card> $cards
     */
    private array $cards;

    /**
     * Constructor for Deck class.
     *
     * Assign array $card to an empty array.
     * Call to function initializeDeck().
     */
    public function __construct()
    {
        $this->cards = [];
        $this->initializeDeck();
    }

    /**
     * Populate array $cards with instans of Card class with suit and value.
     */
    private function initializeDeck(): void
    {
        foreach (self::SUITS as $suit) {
            foreach (self::VALUES as $value) {
                $this->cards[] = new Card($suit, $value);
            }
        }
    }

    /**
     * Get all the cards in the deck.
     * @return array<Card> $cards
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
