<?php

namespace App\Card;

class Card
{
    protected string $suit;
    protected string $value;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function cardString(): string
    {
        return $this->value . " of " . $this->suit;
    }


    public function cardStringLetter(): string
    {
        return "[" . $this->value . " " . $this->suit . "]";
    }

    public static function cardValue(Card $card): int
    {
        $valueConvert = [
            'Jack' => 11,
            'Queen' => 12,
            'King' => 13,
            'Ace' => 14
        ];

        $value = (int)($valueConvert[$card->getValue()] ?? 0);

        if (in_array($card->getValue(), range(2, 10))) {
            $value = (int)$card->getValue();
        }

        return $value;
    }
}
