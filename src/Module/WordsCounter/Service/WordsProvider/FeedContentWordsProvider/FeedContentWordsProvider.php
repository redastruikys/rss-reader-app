<?php

namespace App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider;

use App\Module\RssReader\FeedReaderInterface;
use App\Module\RssReader\TheRegisterFeedReader;
use App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\Sanitizer\DefaultSanitizersCollection;
use App\Module\WordsCounter\Service\WordsProvider\FeedContentWordsProvider\Sanitizer\SanitizersCollection;
use App\Module\WordsCounter\Service\WordsProvider\ContentToStringsSource;
use App\Module\WordsCounter\Service\WordsProvider\WordsCollection;
use App\Module\WordsCounter\Service\WordsProvider\WordsProviderInterface;

class FeedContentWordsProvider implements WordsProviderInterface
{
    const REGEX_NUMBER_LIKE_PATTERN = '/^(([v]|[\p{Sc}])?(\d+(\.\d+)*)([vmb]| ?[\p{Sc}]| ?usd| ?eur| ?gbp)?)$/mi';

    /**
     * @var FeedReaderInterface|TheRegisterFeedReader
     */
    private $feedReader;

    /**
     * @var WordsCollection
     */
    private $wordsCollection;

    /**
     * @var SanitizersCollection
     */
    private $sanitizers;

    public function __construct(FeedReaderInterface $feedReader, SanitizersCollection $sanitizers) {
        $this->feedReader = $feedReader;
        $this->sanitizers = $sanitizers->isEmpty() ? new DefaultSanitizersCollection() : $sanitizers;
        $this->wordsCollection = new WordsCollection();
    }

    public function getWordsCollection(): WordsCollection
    {
        if ($this->wordsCollection->isEmpty()) {
            $this->wordsCollection->setItems($this->getStringsFromContent());
        }

        return $this->wordsCollection;
    }

    /**
     * Processes feed content and returns array of words/strings,
     * each word being stripped of non characters, trimmed etc...
     *
     * @return array|string[]
     */
    private function getStringsFromContent(): array
    {
        $stringSource = new ContentToStringsSource($this->getProcessedContent());

        return $this->filterStrings($stringSource->getStrings());
    }

    private function getProcessedContent(): string
    {
        return $this->sanitizeContent($this->getMergeContent());
    }

    /**
     * Merges feed items content(description & titles) into single string
     */
    private function getMergeContent(): string
    {
        $parts = [];

        foreach ($this->feedReader->getUngroupedItems() as $item) {
            $parts[] = $item->getTitle();
            $parts[] = $item->getDescription();
        }

        return implode(PHP_EOL, $parts);
    }

    /**
     * Returns modified content such as content stripped of HTML tags, decoded html tags such as (&amp;).
     * See DefaultSanitizersCollection class
     *
     * @param string $content
     * @return string
     */
    private function sanitizeContent(string $content): string
    {
        if (!empty($content)) {
            foreach ($this->sanitizers->getItems() as $sanitizer) {
                $content = $sanitizer->sanitize($content);
            }
        }

        return $content;
    }

    /**
     * Remove single-char words and numbers-like expressions such as: 1234, 123.123.322, 1.0.11v, 14.00£
     * @param array|string[] $items
     * @return array
     */
    private function filterStrings(array $items)
    {
        return array_filter($items, function ($item) {
            return !(
                mb_strlen($item) === 1 ||
                preg_match(self::REGEX_NUMBER_LIKE_PATTERN, $item)
            );
        });
    }
}
