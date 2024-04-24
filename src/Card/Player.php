<?php

namespace App\Card;

use App\Card\CardHand;

class Player
{
    private string $name;
    private CardHand $cardHand;


    public function __construct()
    {
        $this->name = '';
        $this->cardHand = new CardHand();
    }


    public function addName(string $name): void
    {
        $this->name = $name;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getPlayerHand(): CardHand
    {
        return $this->cardHand;
    }


    /**
    * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int} $playerBoard
    */
    public function getPlayerBoard(): array
    {
        $playerBoard = [
            'name' => $this->name,
            'hand' => $this->cardHand->handString(),
            'handLetter' => $this->cardHand->handStringLetter(),
            'value' => $this->cardHand->handValue()
            ];

        return $playerBoard;
    }
}
