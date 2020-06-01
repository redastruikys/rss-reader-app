<?php

namespace App\Module\WordsCounter\Service;

use App\Module\WordsCounter\Service\WordsProvider\CountedWordsCollection;
use App\Module\WordsCounter\Service\WordsProvider\WordsProviderInterface;

interface WordsCounterInterface
{
    public function setWordsProvider(WordsProviderInterface $provider): void;

    public function setExcludedWordsProvider(WordsProviderInterface $provider): void;

    public function getCountedWordsCollection(): CountedWordsCollection;
}
