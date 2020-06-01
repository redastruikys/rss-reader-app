<?php

namespace App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\Sanitizer;

class DefaultSanitizersCollection extends SanitizersCollection
{
    public function __construct()
    {
        parent::__construct([
            new HtmlFixSanitizer(),
        ]);
    }
}
