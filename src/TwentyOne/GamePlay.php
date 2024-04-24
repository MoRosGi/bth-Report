<?php

namespace App\TwentyOne;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\CardHand;
use App\Card\Game;
use App\Card\Player;

class GamePlay extends Game
{
    /**
    * @var Player $dealer
    */
    private Player $dealer;


    public function __construct(CardDeck $deck)
    {
        parent::__construct($deck);

        $this->dealer = new Player();
        $this->dealer->addName("Dealer");
    }


    public function getDealer(): object
    {
        return $this->dealer;
    }


    public function dealOnePayer(int $numCards, string $name): void
    {
        foreach ($this->players as $player) {
            if ($player->getName() === $name) {
                $hand = $player->getPlayerHand();
                $cards = $this->deck->drawCard($numCards);

                foreach ($cards as $card) {
                    $hand->addCard($card);
                }
            }
        }
    }


    public function dealDealer(int $numCards): void
    {
        $hand = $this->dealer->getPlayerHand();
        $cards = $this->deck->drawCard($numCards);

        foreach ($cards as $card) {
            $hand->addCard($card);
        }
    }


    /**
    * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int}
    */
    public function getDealerGame(): array
    {
        return $this->dealer->getPlayerBoard();
    }


    /**
    * @return array{name: string, hand: array<string>, handLetter: array<string>, value: int}
    * @throws \Exception When player with the specified name is not found
    */
    public function getPlayerBoardByName(string $name): array
    {
        foreach ($this->players as $player) {
            if ($player->getName() === $name) {
                return $player->getPlayerBoard();
            }
        }

        throw new \Exception("Player '$name' not found.");
    }


    public function endGame(int $dealerValue, int $playerValue): string
    {
        $message = '';

        if ($playerValue > 21 || $dealerValue > $playerValue && $dealerValue < 21 || $dealerValue === $playerValue || $dealerValue == 21) {
            $message = 'Dealer won the game!';
        }
        if ($dealerValue > 21 || $playerValue > $dealerValue && $playerValue < 21 || $playerValue == 21) {
            $message = 'Congrats! You won the game!';
        }

        return $message;
    }

}
