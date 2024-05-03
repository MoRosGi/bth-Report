<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

/**
 * Class representing a deck of cards.
 *
 * Encapsulate methods related to deck.
 *
 * @property array $deck Contains all cards (Card) in the game.
 */
class CardDeck
{
    /**
    * @var array<Card> $deck
    */
    protected array $deck;

    /**
     * Constructor for CardDeck class.
     *
     * Populate array $deck with instans of Card class.
     *
     * @param string $graphic Optional parameter to create graphic cards.
     */
    public function __construct(string $graphic = null)
    {
        $this->deck = [];
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $this->deck[] = $graphic === "graphic" ? new CardGraphic($suit, $value) : new Card($suit, $value);
            }
        }
    }


    /**
     * Return $deck array.
     * @return array<Card> $deck
     */
    public function getDeck(): array
    {
        return $this->deck;
    }

    /**
     * Shuffle $deck array.
     */
    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }

    /**
     * Reset the $deck array to its constructed state.
     *
     * @param string $graphic Optional parameter to create graphic cards.
     */
    public function resetDeck(string $graphic = null): void
    {
        $this->__construct($graphic);
    }


    /**
     * Return an array of string representing the array of Card object given.
     *
     * @param array<Card> $arr The array of Card object.
     * @return array<string> $values
    */
    public static function toString(array $arr): array
    {
        $values = [];
        foreach ($arr as $card) {
            $values[] = $card->cardString();
        }

        return $values;
    }

    /**
     * Count and return the number of Card object in deck.
     *
     * @return int Number of Card object.
     */
    public function countDeck(): int
    {
        return count($this->deck);
    }


    /**
     * Draw a given number of cards from $deck and return it.
     *
     * @param int $num Number of Card object to draw from deck.
     *
     * @return array<Card> $drawCard The array of Card object draw from deck.
     */
    public function drawCard(int $num): array
    {
        $drawKeys = [];
        $drawCard = [];

        $randKeys = array_rand($this->deck, $num);

        foreach((array)$randKeys as $key) {
            array_push($drawKeys, $key);
        }

        foreach ($drawKeys as $key) {
            array_push($drawCard, $this->deck[$key]);
            unset($this->deck[$key]);
        }

        return $drawCard;
    }


    /**
     * Remove the given specific array of Card object from deck.
     *
     * @param array<Card> $arr The array of Card object tp remove.
     * @return CardDeck The CardDeck object.
     */
    public function removeFromDeck(array $arr): CardDeck
    {
        foreach ($arr as $item) {
            $index = array_search($item, $this->deck);

            if ($index !== false) {
                unset($this->deck[$index]);
            }
        }

        return $this;
    }
}
