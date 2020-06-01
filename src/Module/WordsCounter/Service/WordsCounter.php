<?php

namespace App\Module\WordsCounter\Service;

use App\Module\WordsCounter\Model\CountedWord;
use App\Module\WordsCounter\Model\Word;
use App\Module\WordsCounter\Service\WordsProvider\CountedWordsCollection;
use App\Module\WordsCounter\Service\WordsProvider\WordsProviderInterface;

class WordsCounter implements WordsCounterInterface
{
    /**
     * @var WordsProviderInterface
     */
    private $wordsProvider;

    /**
     * @var WordsProviderInterface
     */
    private $excludedWordsProvider;

    /**
     * @var CountedWordsCollection
     */
    private $countedWordsCollection;

    /**
     * WordsCounter constructor.
     */
    public function __construct()
    {
        $this->countedWordsCollection = new CountedWordsCollection();
    }

    public function setWordsProvider(WordsProviderInterface $wordsProvider): void
    {
        $this->wordsProvider = $wordsProvider;
    }

    public function setExcludedWordsProvider(WordsProviderInterface $excludedWordsProvider): void
    {
        $this->excludedWordsProvider = $excludedWordsProvider;
    }

    public function getCountedWordsCollection(): CountedWordsCollection
    {
        if ($this->countedWordsCollection->isEmpty() && $this->wordsProvider) {
            foreach ($this->wordsProvider->getWordsCollection()->getItems() as $word) {
                if ($this->isWordExcluded($word) || $this->isWordAlreadyAdded($word)) {
                    continue;
                }

                $countedWord = new CountedWord($word, $this->getCountOfWordInCollection($word));
                $this->countedWordsCollection->addItem($countedWord);
            }
        }

        return $this->countedWordsCollection;
    }

    private function isWordExcluded(Word $word): bool
    {
        if (!$this->excludedWordsProvider) {
            return false;
        }

        foreach ($this->excludedWordsProvider->getWordsCollection()->getItems() as $excludedWord) {
            if ($word->isEqual($excludedWord)) {
                return true;
            }
        }

        return false;
    }

    private function isWordAlreadyAdded(Word $word): bool
    {
        foreach ($this->countedWordsCollection->getItems() as $item) {
            if ($word->isEqual($item)) {
                return true;
            }
        }

        return false;
    }

    private function getCountOfWordInCollection(Word $word): int
    {
        return array_reduce(
            $this->wordsProvider->getWordsCollection()->getItems(),
            function ($count, Word $item) use ($word) {
                return $count + ((int)$word->isEqual($item));
            },
            0
        );
    }
}
