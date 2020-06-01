<?php

namespace App\Module\WordsCounter\Service;

use App\Module\WordsCounter\Model\CountedWord;
use App\Module\WordsCounter\Service\WordsProvider\CommonEnglishWordsProvider;
use App\Module\WordsCounter\Service\WordsProvider\CountedWordsCollection;
use App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\FeedContentWordsProvider;
use App\Module\WordsCounter\Service\WordsProvider\WordsProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MostPopularWordsCollectionBuilder
{
    const DEFAULT_WORDS_LIMIT = 10;
    const WORDS_LIMIT_CONFIG_KEY = 'app.most_popular_words_limit';

    /**
     * @var WordsProviderInterface
     */
    private $wordsProvider;
    /**
     * @var WordsProviderInterface
     */
    private $excludedWordsProvider;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        FeedContentWordsProvider $wordsProvider,
        CommonEnglishWordsProvider $excludedWordsProvider,
        ContainerInterface $container
    ) {
        $this->wordsProvider = $wordsProvider;
        $this->excludedWordsProvider = $excludedWordsProvider;
        $this->container = $container;
    }

    /**
     * @return array|CountedWord[]
     */
    public function getMostPopularWords(): array
    {
        $wordsCounter = new WordsCounter();
        $wordsCounter->setWordsProvider($this->wordsProvider);
        $wordsCounter->setExcludedWordsProvider($this->excludedWordsProvider);

        $wordsCollection = $wordsCounter->getCountedWordsCollection();

        $this->sortCollection($wordsCollection);
        $this->reduceCollection($wordsCollection);

        return $wordsCollection->getItems();
    }

    private function sortCollection(CountedWordsCollection $collection): void
    {
        $collection->sort(function (CountedWord $a, CountedWord $b) {
            return -($a->getCount() <=> $b->getCount());
        });
    }

    public function reduceCollection(CountedWordsCollection $collection): void
    {
        $limit = $this->getWordsLimitConfig();

        $collection->filter(function (int $key) use ($limit) {
            return $key < $limit;
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getWordsLimitConfig(): int
    {
        if ($this->container->hasParameter(self::WORDS_LIMIT_CONFIG_KEY)) {
            return (int)$this->container->getParameter(self::WORDS_LIMIT_CONFIG_KEY);
        }

        return self::DEFAULT_WORDS_LIMIT;
    }
}
