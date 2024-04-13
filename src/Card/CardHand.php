<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    private $hand;

    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }


    public function getHand(): array
    {
        return $this->hand;
    }


    public function handString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->cardString();
        }

        return $values;
    }
}
