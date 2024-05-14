<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardDeck;
use App\TwentyOne\GamePlay;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwentyOneController extends AbstractController
{
    #[Route("/game", name: "twentyone_home")]
    public function home(): Response
    {
        return $this->render('twenty-one/home.html.twig');
    }


    #[Route("/game/doc", name: "twentyone_documentation")]
    public function documentation(): Response
    {
        return $this->render('twenty-one/doc.html.twig');
    }


    #[Route("/game/init", name: "twentyone_init")]
    public function init(
        SessionInterface $session
    ): Response {
        $session -> clear();

        $gamePlay = new GamePlay(new CardDeck("graphic"));
        $gamePlay->addPlayers(1);
        $session->set("gamePlay", $gamePlay);
        $session->set("end", false);

        return $this->redirectToRoute('twentyone_play');
    }


    #[Route("/game/play", name: "twentyone_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        /**
        * @var GamePlay $gamePlay
        */
        $gamePlay = $session->get("gamePlay");

        $playerValue = $gamePlay->getPlayerBoardByName('Player 1')['value'];
        $dealerValue = $gamePlay->getDealerGame()['value'];

        $end = $session->get("end");

        if ($end === true) {
            $message = $gamePlay->endGame($dealerValue, $playerValue);
            $this->addFlash(
                'notice',
                $message
            );
        }

        $session->set("gamePlay", $gamePlay);

        $data = [
            "playerGame" => $gamePlay->getPlayerBoardByName('Player 1'),
            "dealerGame" => $gamePlay->getDealerGame(),
            "end" => $end
        ];

        return $this->render('twenty-one/play.html.twig', $data);
    }


    #[Route("/game/draw", name: "twentyone_draw", methods: ['POST'])]
    public function getCard(
        SessionInterface $session
    ): Response {
        /**
        * @var GamePlay $gamePlay
        */
        $gamePlay = $session->get("gamePlay");
        // $playerValue = $gamePlay->getPlayerBoardByName('Player 1')['value'];

        $gamePlay->dealOnePayer(1, "Player 1");
        $playerValue = $gamePlay->getPlayerBoardByName('Player 1')['value'];
        $session->set("gamePlay", $gamePlay);

        if ($playerValue === 21 || $playerValue > 21) {
            $session->set("end", true);
        }

        return $this->redirectToRoute('twentyone_play');
    }


    #[Route("/game/dealer", name: "twentyone_dealer", methods: ['POST'])]
    public function dealer(
        SessionInterface $session
    ): Response {
        /**
        * @var GamePlay $gamePlay
        */
        $gamePlay = $session->get("gamePlay");
        $dealerValue = $gamePlay->getDealerGame()['value'];

        while ($dealerValue <= 17) {
            $gamePlay->dealDealer(1);
            $dealerValue = $gamePlay->getDealerGame()['value'];
            $session->set("gamePlay", $gamePlay);
        }

        $session->set("end", true);

        return $this->redirectToRoute('twentyone_play');
    }
}
