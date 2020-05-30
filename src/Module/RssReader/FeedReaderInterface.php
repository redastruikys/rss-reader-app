<?php

namespace App\Module\RssReader;

interface FeedReaderInterface
{
    public function getItems(): array;
}
