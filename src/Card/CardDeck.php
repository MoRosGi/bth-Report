<?php

namespace App\Card;

use App\Card\Card;

class CardDeck
{
    protected $deck;

    public function __construct($graphic = null)
    {
        $this->deck = [];
        $suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
        $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                if ($graphic === "graphic") {
                    $this->deck[] = new CardGraphic($suit, $value);
                } else {
                    $this->deck[] = new Card($suit, $value);
                }
            }
        }
    }


    public function getDeck(): array
    {
        return $this->deck;
    }


    public function shuffleDeck(): void
    {
        shuffle($this->deck);
    }


    public function resetDeck($graphic = null): void
    {
        $this->__construct($graphic);
    }


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


    public function drawCard(int $num): array
    {
        $drawKeys = [];
        $drawCard = [];

        $randKeys = array_rand($this->deck, $num);

        if (gettype($randKeys) === "integer") {
            array_push($drawKeys, $randKeys);
        } else {
            foreach ($randKeys as $key) {
                array_push($drawKeys, $key);
            }
        }

        foreach ($drawKeys as $key) {
            array_push($drawCard, $this->deck[$key]);
            unset($this->deck[$key]);
        }

        return $drawCard;
    }


    public function removeFromDeck(array $arr): object
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
