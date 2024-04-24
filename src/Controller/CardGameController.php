<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardDeck;
use App\Card\Game;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route("/session", name: "session")]
    public function sessionPrint(
        SessionInterface $session
    ): Response {
        $data = [
            "session" => $session -> all()
        ];

        return $this->render('session.html.twig', $data);
    }


    #[Route("/session/delete", name: "session_delete")]
    public function sessionDelete(
        SessionInterface $session
    ): Response {
        $session -> clear();

        $this->addFlash(
            'notice',
            'Session is cleared!'
        );

        return $this->redirectToRoute('session');
    }


    #[Route("/card", name: "card_init")]
    public function init(
        SessionInterface $session
    ): Response {
        $deck = $session->get("deck");
        $cardDraw = $session->get("card_draw");

        if (!$deck || !$cardDraw) {
            $deck = new CardDeck("graphic");
            $cardDraw = [];

            $session->set("deck", $deck);
            $session->set("card_draw", $cardDraw);
        }

        return $this->render('card/home.html.twig');
    }


    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $deck
        */
        $deck = $session->get("deck");

        /**
        * @var array<Card> $cardDraw
        */
        $cardDraw = $session->get("card_draw");

        $deck->resetDeck("graphic");
        $deck = $deck->removeFromDeck($cardDraw);

        $data = [
            "deck" => $deck->toString($deck->getDeck()),
            "countCard" => $deck->countDeck(),
        ];

        return $this->render('card/deck.html.twig', $data);
    }


    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function deckShuffle(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $deck
        */
        $deck = $session->get("deck");

        $deck->resetDeck("graphic");
        $session->set("deck", $deck);

        $deck->shuffleDeck();

        $cardDraw = [];
        $session->set("card_draw", $cardDraw);

        $data = [
            "deck" => $deck->toString($deck->getDeck()),
            "countCard" => $deck->countDeck(),
        ];

        return $this->render('card/deck-shuffle.html.twig', $data);
    }


    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function deckDraw(
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $deck
        */
        $deck = $session->get("deck");

        /**
        * @var array<Card> $cardDraw
        */
        $cardDraw = $session->get("card_draw");

        if ($deck->countDeck() > 0) {
            $toDraw = $deck->drawCard(1);
            foreach ($toDraw as $card) {
                array_push($cardDraw, $card);
            }
        }

        $session->set("card_draw", $cardDraw);

        $session->set("deck", $deck);

        $data = [
            "countCard" => $deck->countDeck(),
            "cardDraw" => $deck->toString($cardDraw),
        ];

        return $this->render('card/deck-draw.html.twig', $data);
    }


    #[Route("/card/deck/draw/{num}", name: "card_deck_draw_many")]
    public function deckDrawMany(
        int $num,
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $deck
        */
        $deck = $session->get("deck");

        /**
        * @var array<Card> $cardDraw
        */
        $cardDraw = $session->get("card_draw");

        if ($deck->countDeck() > 0 and $num > 0) {
            $toDraw = $deck->drawCard($num);
            foreach ($toDraw as $card) {
                array_push($cardDraw, $card);
            }
        }

        $session->set("card_draw", $cardDraw);

        $session->set("deck", $deck);

        $data = [
            "countCard" => $deck->countDeck(),
            "cardDraw" => $deck->toString($cardDraw),
        ];

        return $this->render('card/deck-draw-many.html.twig', $data);
    }


    #[Route("/card/deck/deal/{players}/{cards}", name: "card_deal")]
    public function deal(
        int $players,
        int $cards,
        SessionInterface $session
    ): Response {

        /**
        * @var CardDeck $deck
        */
        $deck = $session->get("deck");

        /**
        * @var array<Card> $cardDraw
        */
        $cardDraw = $session->get("card_draw");

        $game = new Game($deck);
        $game->addPlayers($players);

        if ($deck->countDeck() > 0 and $cards > 0) {
            $toDraw = $game->dealAllPlayers($cards);

            foreach ($toDraw as $card) {
                array_push($cardDraw, $card);
            }
        }

        $playersGame = $game->getPlayersGame();

        $session->set("card_draw", $cardDraw);

        $session->set("deck", $game->getDeck());

        $data = [
            "playersGame" => $playersGame,
            "countCard" => $deck->countDeck(),
        ];

        return $this->render('card/deal.html.twig', $data);
    }
}
