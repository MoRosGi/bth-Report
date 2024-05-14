<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\Game;
use App\TwentyOne\GamePlay;

use App\Repository\BookRepository;
// use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiTwentyController extends AbstractController
{
    #[Route("/api/game", name: "game")]
    public function game(
        SessionInterface $session
    ): Response {

        /**
        * @var GamePlay $gamePlay
        */
        $gamePlay = $session->get("gamePlay");
        $end = $session->get("end");

        $player = $gamePlay->getPlayerBoardByName('Player 1');
        $dealer = $gamePlay->getDealerGame();

        $data = [
            "Player 1" => [
                "Hand" => $player['handLetter'],
                "Value" => $player['value']],
            "Dealer" => [
                "Hand" => $dealer['handLetter'],
                "Value" => $dealer['value']],
            "GameStatus" => $end,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
