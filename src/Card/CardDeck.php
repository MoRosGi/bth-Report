<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardGraphic;

class CardDeck
{
    /**
    * @var array<Card> $deck
    */
    protected array $deck;

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
    * @return array<Card> $deck
    */
    public function getDeck(): array
    {
        return $this->deck;
    }


    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }


    public function resetDeck(string $graphic = null): void
    {
        $this->__construct($graphic);
    }


    /**
    * @param array<Card> $arr
    * @return array<string> $values
    */
    public function toString(array $arr): array
    {
        $values = [];
        foreach ($arr as $card) {
            $values[] = $card->cardString();
        }

        return $values;
    }


    public function countDeck(): int
    {
        return count($this->deck);
    }


    /**
    * @return array<Card> $drawCard
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
    * @param array<Card> $arr
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
