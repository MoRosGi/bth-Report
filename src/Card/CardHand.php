<?php

namespace App\Card;

use App\Card\Card;

class CardHand
{
    /**
    * @var array<Card> $hand
    */
    private array $hand = [];


    public function addCard(Card $card): void
    {
        $this->hand[] = $card;
    }


    /**
    * @return array<Card> $hand
    */
    public function getHand(): array
    {
        return $this->hand;
    }


    /**
    * @return array<string> $values
    */
    public function handString(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->cardString();
        }

        return $values;
    }


    /**
    * @return array<string> $values
    */
    public function handStringLetter(): array
    {
        $values = [];
        foreach ($this->hand as $card) {
            $values[] = $card->cardStringLetter();
        }

        return $values;
    }


    public function handValue(): int
    {
        $totalValue = 0;
        $aceCount = 0;

        foreach ($this->hand as $card) {
            $totalValue += $card->cardValue($card);

            if ($card->getValue() === 'Ace') {
                $aceCount++;
            }
        }

        while ($totalValue > 21 && $aceCount > 0) {
            $totalValue -= 13;
            $aceCount--;
        }

        return $totalValue;
    }
}
