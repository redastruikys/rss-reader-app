<?php

namespace App\Controller;

use App\Module\RssReader\FeedReaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @var FeedReaderInterface
     */
    private $feedReader;

    public function __construct(FeedReaderInterface $feedReader)
    {
        $this->feedReader = $feedReader;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        //redirect to login page if not yet logged-in
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $feedItemsGroups = $this->feedReader->getItems();

        return $this->render('main/index.html.twig', ['feedItemsGroups' => $feedItemsGroups]);
    }
}
