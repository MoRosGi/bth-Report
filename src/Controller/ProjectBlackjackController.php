<?php

namespace App\Controller;

use App\Project\Game;

use App\Entity\GameStats;
use App\Entity\PlayerStats;
use App\Repository\GameStatsRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectBlackjackController extends AbstractController
{
    #[Route('/proj', name: 'blackjack_init', methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('project/blackjack-init.html.twig');
    }

    #[Route('/proj/init', name: 'blackjack_init_post', methods: ['POST'])]
    public function initPost(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $game = new Game();
        $gameStats = new GameStats();
        $playerStats = new PlayerStats();

        $entityManager = $doctrine->getManager();

        /**
         * @var string $playerName
         */
        $playerName = $request->request->get('player_name');
        $game->getPlayer()->setName($playerName);

        $gameStats->setStart(new DateTime());

        $playerStats->setName($playerName);
        $playerStats->setBalance($game->getPlayer()->getBalance());

        $gameStats->setPlayer($playerStats);
        $playerStats->addGame($gameStats);

        $entityManager->persist($gameStats);
        $entityManager->persist($playerStats);
        $entityManager->flush();

        $session->set("game", $game);
        $session->set("gameId", $gameStats->getId());

        return $this->redirectToRoute('blackjack_round');
    }

    #[Route('/proj/round', name: 'blackjack_round', methods: ['GET'])]
    public function round(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        $data = [
            "playerBalance" => $game->getPlayer()->getBalance(),
            "playerName" => $game->getPlayer()->getName()
        ];

        return $this->render('project/blackjack-round.html.twig', $data);
    }

    #[Route('/proj/round/init', name: 'blackjack_round_post', methods: ['POST'])]
    public function roundPost(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        /**
         * @var Game $game
         */
        $game = $session->get("game");

        /**
         * @var GameStats $gameStats
         */
        $gameStats = $entityManager->getRepository(GameStats::class)->find($session->get("gameId"));

        if ($game->getGameState() === "round end") {
            $game->getPlayer()->resetHand();
            $game->getDealer()->resetHand();
        }

        /**
         * @var int $numHand
         */
        $numHand = $request->request->get('num_hand');
        $addHands = $game->addHands($numHand);

        if (!$addHands) {
            $this->addFlash(
                'warning',
                'Not enough balance to play that number of hands'
            );
            return $this->redirectToRoute('blackjack_round');
        } elseif ($addHands) {
            $numRound = $gameStats->getRound();
            $numRound += 1;
            $gameStats->setRound($numRound);
            $entityManager->flush();

            $session->set("game", $game);

            return $this->redirectToRoute('blackjack_bet');
        }
    }

    #[Route('/proj/bet', name: 'blackjack_bet', methods: ['GET'])]
    public function bet(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        $data = [
            "playerHand" => $game->getPlayer()->getHands(),
            "playerBalance" => $game->getPlayer()->getBalance(),
            "playerName" => $game->getPlayer()->getName(),
            "shoe" => $game->getShoe()->countCards()
        ];

        return $this->render('project/blackjack-bet.html.twig', $data);
    }

    #[Route('/proj/bet/init', name: 'blackjack_bet_post', methods: ['POST'])]
    public function betPost(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine,
        GameStatsRepository $gameStatsRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        /**
         * @var Game $game
         */
        $game = $session->get("game");

        /**
         * @var GameStats $gameStats
         */
        $gameStats = $entityManager->getRepository(GameStats::class)->find($session->get("gameId"));

        $betsRequest = $request->request->all();

        $bets = [];
        $sumBets = 0;

        foreach ($betsRequest as $key => $value) {
            $bets[$key] = intval($value);
            $sumBets += intval($value);
        }

        $distributeBets = $game->distributeBets($sumBets, $bets);

        if (!$distributeBets) {
            $this->addFlash(
                'warning',
                'Not enough balance for that amount of bet'
            );
            return $this->redirectToRoute('blackjack_bet');
        } elseif ($distributeBets) {
            $gameStatsRepository->updatePlayerBet($gameStats, $game);
            $entityManager->flush();

            $session->set("game", $game);

            return $this->redirectToRoute('blackjack_play');
        }
    }

    #[Route('/proj/play', name: 'blackjack_play', methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {

        /**
         * @var Game $game
         */
        $game = $session->get("game");

        $data = [
            "playerHand" => $game->getPlayer()->getHands(),
            "dealerHand" => $game->getDealer()->getHand(),
            "playerBalance" => $game->getPlayer()->getBalance(),
            "playerName" => $game->getPlayer()->getName(),
            "shoe" => $game->getShoe()->countCards(),
            "activeHandIndex" => $game->getActiveHandIndex(),
            "gameState" => $game->getGameState()
        ];

        return $this->render('project/blackjack-play.html.twig', $data);
    }

    #[Route('/proj/play/deal', name: 'blackjack_deal_post', methods: ['POST'])]
    public function dealPost(
        SessionInterface $session,
        ManagerRegistry $doctrine,
        GameStatsRepository $gameStatsRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        /**
         * @var Game $game
         */
        $game = $session->get("game");

        /**
         * @var GameStats $gameStats
         */
        $gameStats = $entityManager->getRepository(GameStats::class)->find($session->get("gameId"));

        $game->deal();
        $game->setEventualBlackjack();
        $game->setTurn($game->getActiveHandIndex());

        if ($game->getGameState() == "count win") {
            $game->countWins();
        }

        if ($game->getGameState() == "round end") {
            $gameStatsRepository->updatePlayerWin($gameStats, $game);
            $entityManager->flush();
        }

        $session->set("game", $game);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route('/proj/play/action', name: 'blackjack_action_post', methods: ['POST'])]
    public function actionPost(
        SessionInterface $session,
        Request $request,
        ManagerRegistry $doctrine,
        GameStatsRepository $gameStatsRepository
    ): Response {
        $entityManager = $doctrine->getManager();

        /**
         * @var Game $game
         */
        $game = $session->get("game");

        /**
         * @var GameStats $gameStats
         */
        $gameStats = $entityManager->getRepository(GameStats::class)->find($session->get("gameId"));

        /**
         * @var string $actionRequest
         */
        $actionRequest = $request->request->get('action');

        $game->playerAction($actionRequest);
        $game->setTurn($game->getActiveHandIndex());

        if ($game->getGameState() == "count win") {
            $game->countWins();
        }

        if ($game->getGameState() == "round end") {
            $gameStatsRepository->updatePlayerWin($gameStats, $game);
            $entityManager->flush();
        }

        $session->set("game", $game);

        return $this->redirectToRoute('blackjack_play');
    }

    #[Route('/proj/play/save', name: 'blackjack_save', methods: ['POST'])]
    public function savePost(
        SessionInterface $session,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        /**
         * @var GameStats $gameStats
         */
        $gameStats = $entityManager->getRepository(GameStats::class)->find($session->get("gameId"));

        $gameStats->setStop(new DateTime());
        $entityManager->flush();

        return $this->redirectToRoute('blackjack_init');
    }
}
