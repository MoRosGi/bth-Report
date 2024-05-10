<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/library', name: 'library_home', methods: ['GET'])]
    public function index(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('book/index.html.twig', $data);
    }

    #[Route('/library/show/{id}', name: 'book_by_id', methods: ['GET'])]
    public function showBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('book/book.html.twig', $data);
    }

    #[Route('/library/add', name: 'library_add_get', methods: ['GET'])]
    public function addBook(): Response
    {
        return $this->render('book/add.html.twig');
    }

    #[Route('/library/add', name: 'library_add_post', methods: ['POST'])]
    public function addBookPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        /** @var string $title */
        $title = $request->request->get('title');

        /** @var string $isbn */
        $isbn = $request->request->get('isbn');

        /** @var string $author */
        $author = $request->request->get('author');

        /** @var string $imagePath */
        $imagePath = $request->request->get('imagePath');

        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImagePath($imagePath);

        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash('notice', 'New book, ' . '* ' . $book->getTitle() . ' *' . ' added to the library');

        return $this->redirectToRoute('library_home');
    }


    #[Route('/library/update/{id}', name: 'library_update_get', methods: ['GET'])]
    public function updateBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'library_update_post', methods: ['POST'])]
    public function updateBookPost(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        /** @var string $title */
        $title = $request->request->get('title');

        /** @var string $isbn */
        $isbn = $request->request->get('isbn');

        /** @var string $author */
        $author = $request->request->get('author');

        /** @var string $imagePath */
        $imagePath = $request->request->get('imagePath');

        if ($book instanceof Book) {
            $book->setTitle($title);
            $book->setIsbn($isbn);
            $book->setAuthor($author);
            $book->setImagePath($imagePath);
        }

        $entityManager->flush();

        $this->addFlash('notice', 'Book has been updated');

        return $this->redirectToRoute('library_home');
    }

    #[Route('/library/delete/{id}', name: 'book_delete_get', methods: ['GET'])]
    public function deleteBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('book/delete.html.twig', $data);
    }

    #[Route('/library/delete/{id}', name: 'book_delete_post', methods: ['POST'])]
    public function deleteBookPost(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if ($book instanceof Book) {
            $entityManager->remove($book);
        }

        $entityManager->flush();

        $this->addFlash('warning', 'Book has been remove from the library');

        return $this->redirectToRoute('library_home');
    }

    #[Route('/library/reset', name: 'library_reset', methods: ['GET', 'POST'])]
    public function resetLibrary(
        ManagerRegistry $doctrine
    ): Response {
        $connection = $doctrine->getConnection();

        $file = '../app/sql/backup.sql';
        $query = file_get_contents($file);

        /** @phpstan-ignore-next-line */
        $connection->executeStatement($query);
        $doctrine->getManager()->flush();

        $this->addFlash('warning', 'The library database has been reset');

        return $this->redirectToRoute('library_home');
    }
}
