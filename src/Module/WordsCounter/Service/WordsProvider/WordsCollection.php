<?php

namespace App\Module\WordsCounter\Service\WordsProvider;

use App\Common\Collection\AbstractCollection;
use App\Module\WordsCounter\Model\Word;

/**
 * @method getItems() : array|Word[]
 */
class WordsCollection extends AbstractCollection
{
    /**
     * @param array|string[]|Word[] $items
     */
    public function setItems(array $items)
    {
        parent::setItems($this->mapToWords($items));
    }

    /**
     * @param string|Word $item
     */
    public function addItem($item)
    {
        parent::addItem(($item instanceof Word) ? $item : new Word($item));
    }

    /**
     * @param array|string[]|Word[] $items
     * @return array|Word[]
     */
    private function mapToWords($items): array
    {
        return array_map(function ($item) {
            return ($item instanceof Word) ? $item : new Word($item);
        }, $items);
    }
}
