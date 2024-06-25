<?php

namespace App\Repository;

use App\Entity\GameStats;
use App\Entity\PlayerStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use App\Project\Game;

/**
 * @extends ServiceEntityRepository<GameStats>
 */
class GameStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameStats::class);
    }

    public function updatePlayerBet(GameStats $gameStats, Game $game): void
    {
        /**
         * @var PlayerStats $player
         */
        $player = $gameStats->getPlayer();

        $player->setBalance($game->getPlayer()->getBalance());
        $totalBet = $player->getBet();

        foreach ($game->getPlayer()->getHands() as $hand) {
            $totalBet += $hand->getBet();
        }

        $player->setBet($totalBet);
    }

    public function updatePlayerWin(GameStats $gameStats, Game $game): void
    {
        /**
         * @var PlayerStats $player
         */
        $player = $gameStats->getPlayer();

        $totalWinning = $player->getWinning();

        foreach ($game->getPlayer()->getHands() as $hand) {
            $totalWinning += $hand->getWinning();
        }

        $player->setBalance($game->getPlayer()->getBalance());
        $player->setWinning($totalWinning);

        if ($game->getPlayer()->getBalance() < 1) {
            $gameStats->setStop(new DateTime());
        }
    }
}
