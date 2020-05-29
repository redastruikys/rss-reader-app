<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return new Response('Hello RSS app');
    }
}
