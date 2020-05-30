<?php

namespace App\Module\RssReader;

use FeedIo\Feed\NodeInterface;

class FeedItem
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string;
     */
    private $link;

    public function __construct(NodeInterface $node)
    {
        $this->title = $node->getTitle();
        $this->description = $node->getDescription();
        $this->date = $node->getLastModified();
        $this->link = $node->getLink();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function getFormattedDate($format = self::DATE_FORMAT): string
    {
        return $this->date ? $this->date->format($format) : '';
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
