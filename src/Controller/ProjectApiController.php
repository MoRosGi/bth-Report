<?php

namespace App\Controller;

use App\Project\Game;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectApiController extends AbstractController
{
    #[Route("/proj/api/shoe", name: "project_api_shoe", methods: ['GET'])]
    public function getShoe(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        if (!$game) {
            $data = [
                "Shoe" => 'No game start yet, start a game to see the api shoe.'
            ];
        } elseif ($game) {
            $data = [
                "Number of card left" => $game->getShoe()->countCards(),
                "Shoe" => $game->getShoe()->toString()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/proj/api/state", name: "project_api_state", methods: ['GET'])]
    public function getState(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        if (!$game) {
            $data = [
                "Game State" => 'No game start yet, start a game to see the game state.'
            ];
        } elseif ($game) {
            $data = [
                "Game State" => $game->getGameState()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/proj/api/player", name: "project_api_player", methods: ['GET'])]
    public function getPlayer(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        if (!$game) {
            $data = [
                "Player Hand" => 'No game start yet, start a game to see the Players hand.'
            ];
        } elseif ($game) {
            $playersHands = $game->getPlayer()->getHands();
            $playersHandsCount = count($playersHands);
            $handsToString = [];

            for ($i = 0; $i < $playersHandsCount; $i++) {
                $handsToString[] = $playersHands[$i]->toString();
            }

            $data = [
                "Players Hand" => $handsToString
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/proj/api/dealer", name: "project_api_dealer", methods: ['GET'])]
    public function getDealer(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        if (!$game) {
            $data = [
                "Dealers Hand" => 'No game start yet, start a game to see the Dealers hand.'
            ];
        } elseif ($game) {
            $data = [
                "Dealers Hand" => $game->getDealer()->getHand()->toString()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/proj/api/draw", name: "project_api_draw", methods: ['POST'])]
    public function postDraw(
        SessionInterface $session
    ): Response {
        /**
         * @var Game $game
         */
        $game = $session->get("game");

        if (!$game) {
            $data = [
                "Deal a card" => 'No game start yet, start a game to draw a card from shoe.'
            ];
        } elseif ($game) {
            $data = [
                "Card draw" => $game->getShoe()->dealCard()->toString(),
                "Number of card in shoe" => $game->getShoe()->countCards()
            ];
        }

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
