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

class ApiController extends AbstractController
{
    #[Route("/api", name: "api")]
    public function api(
        SessionInterface $session
    ): Response {
        $apiDeck = $session->get("api_deck");
        $apiDraw = $session->get("api_draw");
        $gamePlay = $session->get("gamePlay");
        $end = $session->get("end");

        if (!$apiDeck || !$apiDraw) {
            $apiDeck = new CardDeck();
            $apiDraw = [];

            $session->set("api_deck", $apiDeck);
            $session->set("api_draw", $apiDraw);
        }

        if (!$gamePlay || !$end) {
            $gamePlay = new GamePlay(new CardDeck("graphic"));
            $gamePlay->addPlayers(1);
            $session->set("gamePlay", $gamePlay);
            $session->set("end", false);
        }

        return $this->render('api.html.twig');
    }


    #[Route("/api/quote", name: "quote")]
    public function quote(
    ): Response {
        $quote = ["Either you run the day or the day runs you.", "What happens is not as important as how you react to what happens.", "Poor eyes limit your sight; poor vision limits your deeds.", "The only true wisdom is in knowing you know nothing."];
        $randomQuote = array_rand($quote);
        $quoteDay = $quote[$randomQuote];

        $currentDate = date('Y-m-d');
        date_default_timezone_set('Europe/Stockholm');
        $timestamp = date("h:i:sa");

        $data = [
            'Date of today:' => $currentDate,
            'Quote for today:' => $quoteDay,
            'Quote generated at:' => $timestamp
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
