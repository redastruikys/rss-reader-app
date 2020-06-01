<?php

namespace App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\Sanitizer;

interface SanitizerInterface
{
    public function sanitize(string $content): string;
}
