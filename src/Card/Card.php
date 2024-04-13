<?php

namespace App\Card;

class Card
{
    protected $suit;
    protected $value;

    public function __construct($suit, $value)
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
}
