<?php

namespace App\Controller;

use App\Repository\GameStatsRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectMainController extends AbstractController
{
    #[Route('/proj/about', name: 'project_about')]
    public function about(): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route('/proj/about/database', name: 'project_database')]
    public function database(): Response
    {
        return $this->render('project/database.html.twig');
    }

    #[Route('/proj/statistics', name: 'project_statistics', methods: ['GET'])]
    public function statistics(
        GameStatsRepository $gameStatsRepository
    ): Response {
        $gameStats = $gameStatsRepository->findAll();

        $data = [
            'gameStats' => $gameStats
        ];
        return $this->render('project/statistics.html.twig', $data);
    }

    #[Route('/proj/reset', name: 'database_reset', methods: ['GET', 'POST'])]
    public function resetDatabase(
        ManagerRegistry $doctrine
    ): Response {
        $connection = $doctrine->getConnection();

        $file = '../app/sql/backup_project.sql';
        $query = file_get_contents($file);

        /** @phpstan-ignore-next-line */
        $connection->executeStatement($query);
        $doctrine->getManager()->flush();

        $this->addFlash('warning', 'The statistics database has been reset');

        return $this->redirectToRoute('project_statistics');
    }

    #[Route("/proj/api", name: "project_api")]
    public function homeApi(): Response
    {
        return $this->render('project/api.html.twig');
    }

    #[Route("/proj/session", name: "project_session")]
    public function session(
        SessionInterface $session
    ): Response {
        $data = [
            "session" => $session->all()
        ];

        return $this->render('project/session.html.twig', $data);
    }

    #[Route("/proj/session/delete", name: "project_session_delete")]
    public function sessionDelete(
        SessionInterface $session
    ): Response {
        $session->clear();

        return $this->redirectToRoute('project_session');
    }
}
