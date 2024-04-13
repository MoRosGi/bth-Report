<?php

namespace App\Card;

use App\Card\CardDeck;
use App\Card\Player;
use App\Card\CardHand;

class Game
{
    private $players;
    private $deck;

    public function __construct(int $numPlayer, CardDeck $deck)
    {
        $this->players = [];

        for ($i = 1; $i <= $numPlayer; $i++) {
            $this->players[] = new Player("Player " . $i, new CardHand());
        }

        $this->deck = $deck;
    }


    public function getDeck(): object
    {
        return $this->deck;
    }


    public function dealCards($numCards): array
    {
        $cardDraw = [];
        foreach ($this->players as $player) {
            $hand = $player->getHand();
            $cards = $this->deck->drawCard($numCards);

            foreach ($cards as $card) {
                $hand->addCard($card);
                array_push($cardDraw, $card);
            }
        }

        return $cardDraw;
    }


    public function getPlayerGame(): array
    {
        $playersGame = [];
        foreach ($this->players as $player) {
            $playersGame[] = [
                'name' => $player->getName(),
                'hand' => $player->getHand()->handString()
            ];
        }

        return $playersGame;
    }
}
