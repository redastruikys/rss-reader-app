<?php

namespace App\Module\WordsCounter\Model;

class CountedWord extends Word
{
    private $count;

    public function __construct(string $text, int $count = 0)
    {
        parent::__construct($text);
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
