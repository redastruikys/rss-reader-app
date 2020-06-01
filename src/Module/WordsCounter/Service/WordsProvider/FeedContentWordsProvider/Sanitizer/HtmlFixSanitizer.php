<?php

namespace App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\Sanitizer;

class HtmlFixSanitizer implements SanitizerInterface
{
    public function sanitize(string $content): string
    {
        return html_entity_decode(strip_tags($content));
    }
}
