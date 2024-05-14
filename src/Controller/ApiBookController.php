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

class ApiBookController extends AbstractController
{
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

    #[Route('/api/library/book/{isbn}', name: 'api_library_isbn', methods: ['GET'])]
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
