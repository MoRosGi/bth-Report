<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
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
}
