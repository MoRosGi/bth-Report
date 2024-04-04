<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JsonController
{
    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
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
