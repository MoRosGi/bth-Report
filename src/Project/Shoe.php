<?php

namespace App\Project;

use App\Project\Deck;

/**
 * Class representing a shoe of cards.
 *
 * Encapsulate methods related to a shoe object.
 *
 * @property array $cards Contains all cards in the shoe.
 * @property int $numOfDeck The number of decks used in the shoe.
 */
class Shoe
{
    /**
     * @var array<Card> $cards
     */
    private array $cards;
    private int $numOfDeck;

    /**
     * Constructor for Shoe class.
     *
     * Assign parameter to int $numOfDeck.
     * Assign array $cards to an empty array.
     * Call to function initializeShoe().
     * Use build-in function shuffle on array $cards.
     *
     * @param int $numOfDeck The number of decks to use to initiate the shoe.
     */
    public function __construct(int $numOfDeck)
    {
        $this->numOfDeck = $numOfDeck;
        $this->cards = [];
        $this->initializeShoe();
        shuffle($this->cards);
    }

    /**
     * Populate array $cards with instans of Deck.
     */
    private function initializeShoe(): void
    {
        for ($i = 0; $i < $this->numOfDeck; $i++) {
            $deck = new Deck();
            $cards = $deck->getCards();

            foreach ($cards as $card) {
                $this->cards[] = $card;
            }
        }
    }

    /**
     * Use build-in function count to calculate the number of cards in the shoe.
     * @return int The number of cards in the array $cards.
     */
    public function countCards(): int
    {
        return count($this->cards);
    }

    /**
     * Take a card from array $card and return it.
     * @return Card
     */
    public function dealCard(): Card
    {
        return array_shift($this->cards);
    }

    /**
     * Get the string representation of the array $cards.
     * @return array<string>
     */
    public function toString(): array
    {
        $values = [];
        foreach ($this->cards as $card) {
            $values[] = $card->toString();
        }

        return $values;
    }
}
