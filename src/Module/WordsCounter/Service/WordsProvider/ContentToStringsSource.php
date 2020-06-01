<?php

namespace App\Module\WordsCounter\Service\WordsProvider;

/**
 * Returns words extracted form content. Output strings are trimmed from non-word characters
 */
class ContentToStringsSource implements StringsSourceInterface
{
    const REGEX_WORD_PATTERN = '/(?<word>\S+)/u';
    const REGEX_TRIM_PATTERN = '/((^([\W]+))|([\W]+)$)/mu';

    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getStrings(): array
    {
        return $this->extractWordsFromContent();
    }

    private function extractWordsFromContent(): array
    {
        if (preg_match_all(self::REGEX_WORD_PATTERN, $this->content, $matches)) {
            [, $words] = $matches;

            return array_filter($this->trimWords($words));
        }

        return [];
    }

    private function trimWords(array $items): array
    {
        return array_map(function (string  $item) {
            return preg_replace(self::REGEX_TRIM_PATTERN, '', $item);
        }, $items);
    }
}
