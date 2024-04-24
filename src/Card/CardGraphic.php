<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    public function __construct(string $suit, string $value)
    {
        parent::__construct($suit, $value);
    }

    public function cardString(): string
    {
        $symbols = [
            'Hearts' => '♥',
            'Diamonds' => '♦',
            'Clubs' => '♣',
            'Spades' => '♠',
            'Jack' => 'J',
            'Queen' => 'Q',
            'King' => 'K',
            'Ace' => 'A'
        ];

        $suitSymbol = $symbols[$this->suit] ?? '';
        $valueSymbol = $symbols[$this->value] ?? '';

        if (in_array($this->value, range(2, 10))) {
            $valueSymbol = $this->value;
        }

        return "[" . $valueSymbol . $suitSymbol . "]";
    }


    public function cardStringLetter(): string
    {
        return parent::cardStringLetter();
    }
}
