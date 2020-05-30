<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        //redirect to login page if not yet logged-in
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('main/index.html.twig');
    }
}
