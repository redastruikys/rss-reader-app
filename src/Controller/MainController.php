<?php

namespace App\Controller;

use App\Module\RssReader\FeedReaderInterface;
use App\Module\WordsCounter\Model\CountedWord;
use App\Module\WordsCounter\Service\MostPopularWordsCollectionBuilder;
use App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\FeedContentWordsProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @var FeedReaderInterface
     */
    private $feedReader;

    /**
     * @var FeedContentWordsProvider
     */
    private $feedContentWordsProvider;

    /**
     * @var MostPopularWordsCollectionBuilder
     */
    private $popularWordsCollectionBuilder;

    public function __construct(
        FeedReaderInterface $feedReader,
        MostPopularWordsCollectionBuilder $popularWordsCollectionBuilder,
        FeedContentWordsProvider $feedContentWordsProvider
    ) {
        $this->feedReader = $feedReader;
        $this->feedContentWordsProvider = $feedContentWordsProvider;
        $this->popularWordsCollectionBuilder = $popularWordsCollectionBuilder;
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

        return $this->render('main/index.html.twig', [
            'feedItemsGroups' => $this->getFeedItemsGroups(),
            'countedWords' => $this->getCountedWords(),
        ]);
    }

    private function getFeedItemsGroups(): array
    {
        return $this->feedReader->getItems();
    }

    /**
     * @return array|CountedWord[]
     */
    private function getCountedWords(): array
    {
        return $this->popularWordsCollectionBuilder->getMostPopularWords();
    }
}
