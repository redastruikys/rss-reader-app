<?php

namespace App\Module\RssReader;

use App\Common\Collection\AbstractCollection;

/**
 * @method getItems() : array|FeedItemsGroup[]
 */
class FeedItemsGroup extends AbstractCollection
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name, array $items = [])
    {
        parent::__construct($items);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
