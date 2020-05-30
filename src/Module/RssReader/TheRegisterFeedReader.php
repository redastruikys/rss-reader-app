<?php

namespace App\Module\RssReader;

use FeedIo\FeedIo;

class TheRegisterFeedReader implements FeedReaderInterface
{
    const SOURCE_URL = 'https://www.theregister.co.uk/software/headlines.atom';
    const GROUP_NAME_DATE_FORMAT = 'F d, Y';

    /**
     * @var FeedIo
     */
    private $feedIo;

    /**
     * @var string
     */
    private $sourceUrl;

    /**
     * @var FeedItemsCollection
     */
    private $items;

    /**
     * @var FeedItemsGroupsCollection
     */
    private $itemsGroups;

    public function __construct(FeedIo $feedIo, $sourceUrl = self::SOURCE_URL)
    {
        $this->feedIo = $feedIo;
        $this->sourceUrl = $sourceUrl;
        $this->items = new FeedItemsCollection();
        $this->itemsGroups = new FeedItemsGroupsCollection();
    }

    /**
     * @return array|FeedItemsGroup[]
     */
    public function getItems(): array
    {
        if ($this->itemsGroups->isEmpty()) {
            $this->readFeedItems();

            foreach ($this->items->getItems() as $item) {
                $key = $this->formatGroupKeyFromDate($item);

                if (!$this->itemsGroups->offsetExists($key)) {
                    $this->itemsGroups->offsetSet($key, new FeedItemsGroup($this->formatGroupName($item)));
                }

                $this->itemsGroups->offsetGet($key)->addItem($item);
            }
        }

        return $this->itemsGroups->getItems();
    }

    private function readFeedItems(): void
    {
        if ($this->items->isEmpty()) {
            $feed = $this->feedIo
                ->read($this->sourceUrl)
                ->getFeed();

            foreach ($feed as $item) {
                $this->items->addItem(new FeedItem($item));
            }
        }
    }

    private function formatGroupName(FeedItem $item): string
    {
        return $item->getFormattedDate(self::GROUP_NAME_DATE_FORMAT);
    }

    private function formatGroupKeyFromDate(FeedItem $item): string
    {
        return $item->getFormattedDate();
    }
}
