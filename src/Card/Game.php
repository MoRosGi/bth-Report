<?php

namespace App\Card;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\Player;

class Game
{
    /**
    * @var array<Player> $players
    */
    protected array $players;

    /**
    * @var CardDeck $deck
    */
    protected CardDeck $deck;


    public function __construct(CardDeck $deck)
    {
        $this->deck = $deck;
    }


    public function addPlayers(int $numPlayer): void
    {
        $this->players = [];

        for ($i = 1; $i <= $numPlayer; $i++) {
            $player = new Player();
            $player->addName("Player " . $i);
            $this->players[] = $player;
        }
    }


    public function getDeck(): object
    {
        return $this->deck;
    }


    /**
    * @return array<Card> $cardDraw
    */
    public function dealAllPlayers(int $numCards): array
    {
        $cardDraw = [];
        foreach ($this->players as $player) {
            $hand = $player->getPlayerHand();
            $cards = $this->deck->drawCard($numCards);

            foreach ($cards as $card) {
                $hand->addCard($card);
                array_push($cardDraw, $card);
            }
        }

        return $cardDraw;
    }


    /**
    * @return array<int<0, max>, array{name: string, hand: array<string>, handLetter: array<string>, value: int}> $playersGame
    */
    public function getPlayersGame(): array
    {
        $playersGame = [];
        foreach ($this->players as $player) {
            $playersGame[] = $player->getPlayerBoard();
        }

        return $playersGame;
    }
}
