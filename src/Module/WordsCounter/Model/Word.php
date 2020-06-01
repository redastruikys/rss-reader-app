<?php

namespace App\Module\WordsCounter\Model;

class Word
{
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function isEqual(Word $word, $caseSensitive = false): bool
    {
        if ($caseSensitive) {
            return strcmp($this, $word) === 0;
        }

        return strcasecmp($this, $word) === 0;
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
