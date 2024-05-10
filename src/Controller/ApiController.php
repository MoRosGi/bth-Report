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

        if (!$apiDeck || !$apiDraw) {
            $apiDeck = new CardDeck();
            $apiDraw = [];

            $session->set("api_deck", $apiDeck);
            $session->set("api_draw", $apiDraw);
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


    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function apiDeck(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $apiDeck
        */
        $apiDeck = $session->get("api_deck");

        /**
        * @var array<Card> $apiDraw
        */
        $apiDraw = $session->get("api_draw");

        $apiDeck->resetDeck();
        $apiDeck = $apiDeck->removeFromDeck($apiDraw);

        $data = [
            "deck" => $apiDeck->toString($apiDeck->getDeck()),
            "countCard" => $apiDeck->countDeck(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


    #[Route("/api/deck/shuffle", name: "api_shuffle", methods: ['POST'])]
    public function apiShuffle(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $apiDeck
        */
        $apiDeck = $session->get("api_deck");

        $apiDeck->shuffleDeck();

        $session->set("api_deck", $apiDeck);

        $data = [
            "deck" => $apiDeck->toString($apiDeck->getDeck()),
            "countCard" => $apiDeck->countDeck(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


    #[Route("/api/deck/draw", name: "api_draw", methods: ['POST'])]
    public function apiDraw(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $apiDeck
        */
        $apiDeck = $session->get("api_deck");

        /**
        * @var array<Card> $apiDraw
        */
        $apiDraw = $session->get("api_draw");

        if ($apiDeck->countDeck() > 0) {
            $toDraw = $apiDeck->drawCard(1);
            foreach ($toDraw as $card) {
                array_push($apiDraw, $card);
            }
        }

        $session->set("api_draw", $apiDraw);

        $apiDeck->resetDeck();
        $apiDeck = $apiDeck->removeFromDeck($apiDraw);

        $session->set("api_deck", $apiDeck);

        $data = [
            "cardDraw" => $apiDeck->toString($apiDraw),
            "countCard" => $apiDeck->countDeck(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


    #[Route("/api/deck/draw/many", name: "api_draw_many", methods: ['POST'])]
    public function apiDrawMany(
        Request $request,
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $apiDeck
        */
        $apiDeck = $session->get("api_deck");

        /**
        * @var array<Card> $apiDraw
        */
        $apiDraw = $session->get("api_draw");

        /**
        * @var int $numCard
        */
        $numCard = $request->request->get('num_card');

        if ($apiDeck->countDeck() > 0) {
            $toDraw = $apiDeck->drawCard($numCard);
            foreach ($toDraw as $card) {
                array_push($apiDraw, $card);
            }
        }

        $apiDeck->resetDeck();
        $apiDeck = $apiDeck->removeFromDeck($apiDraw);

        $session->set("api_draw", $apiDraw);

        $session->set("api_deck", $apiDeck);

        $data = [
            "cardDraw" => $apiDeck->toString($apiDraw),
            "countCard" => $apiDeck->countDeck(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


    #[Route("/api/deck/deal", name: "api_deal", methods: ['POST'])]
    public function apiDeal(
        Request $request,
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $apiDeck
        */
        $apiDeck = $session->get("api_deck");

        /**
        * @var array<Card> $apiDraw
        */
        $apiDraw = $session->get("api_draw");

        /**
        * @var int $numCard
        */
        $numCard = $request->request->get('num_card');

        /**
        * @var int $numPlayer
        */
        $numPlayer = $request->request->get('num_player');

        $game = new Game($apiDeck);
        $game->addPlayers($numPlayer);

        if ($apiDeck->countDeck() > 0 and $numCard > 0) {
            $toDraw = $game->dealAllPlayers($numCard);

            foreach ($toDraw as $card) {
                array_push($apiDraw, $card);
            }
        }

        $playersGame = $game->getPlayersGame();

        $session->set("api_draw", $apiDraw);

        $session->set("api_deck", $game->getDeck());

        $data = [
            "playersGame" => $playersGame,
            "countCard" => $apiDeck->countDeck(),
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }


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

    #[Route('/api/library/books', name: 'api_library', methods: ['GET'])]
    public function index(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/product/show/{isbn}', name: 'api_library_isbn', methods: ['GET'])]
    public function showProductById(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository
            ->findIsbn($isbn);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
