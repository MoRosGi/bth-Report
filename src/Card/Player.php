<?php

namespace App\Card;

use App\Card\CardDeck;

class Player
{
    private $name;
    private $hand;

    public function __construct($name = null, $hand = null)
    {
        $this->name = $name;
        $this->hand = $hand;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHand(): object
    {
        return $this->hand;
    }
}
