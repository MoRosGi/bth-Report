<?php

namespace App\Card;

use App\Card\Card;

class CardGraphic extends Card
{
    public function __construct($suit, $value)
    {
        parent::__construct($suit, $value);
    }

    public function cardString(): string
    {
        $suitSymbol = '';
        $valueSymbol = '';

        switch ($this->suit) {
            case 'Hearts':
                $suitSymbol = '♥';
                break;
            case 'Diamonds':
                $suitSymbol = '♦';
                break;
            case 'Clubs':
                $suitSymbol = '♣';
                break;
            case 'Spades':
                $suitSymbol = '♠';
                break;
        }

        switch ($this->value) {
            case 'Jack':
                $valueSymbol = 'J';
                break;
            case 'Queen':
                $valueSymbol = 'Q';
                break;
            case 'King':
                $valueSymbol = 'K';
                break;
            case 'Ace':
                $valueSymbol = 'A';
                break;
            default:
                if (in_array($this->value, range(2, 10))) {
                    $valueSymbol = $this->value;
                }
                break;
        }

        return "[" . $valueSymbol . $suitSymbol . "]";
    }
}
