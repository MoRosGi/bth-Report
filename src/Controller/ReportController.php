<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/lucky", name: "lucky")]
    public function lucky(): Response
    {
        $symbol = ['｡･(ू˃̣̣̣̣̣̣ ꞈ˂̣̣̣̣̣̣ ू)', 'ᚍ〣( º ﹏º )〣ᚍ', '(•ˋ _ ˊ•)', 'o(〃＾▽＾〃)o', '((╬◣﹏◢))', '＼＼٩(๑`^´๑)۶／／', '╮(︶▽︶)╭ ', '(μ_μ)'];

        $randomIndex = array_rand($symbol);
        $lucky = $symbol[$randomIndex];
        $data = [
            'lucky' => $lucky
        ];

        return $this->render('lucky.html.twig', $data);
    }
}