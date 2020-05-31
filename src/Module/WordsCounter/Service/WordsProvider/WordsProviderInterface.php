<?php

namespace App\Module\WordsCounter\Service\WordsProvider;

interface WordsProviderInterface
{
    public function getWordsCollection(): WordsCollection;
}
