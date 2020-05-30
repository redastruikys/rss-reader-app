<?php

namespace App\Common\Collection;

interface CollectionInterface extends \ArrayAccess, \Countable, \Iterator
{
    public function addItem($item);

    public function getItems(): array;

    public function setItems(array $items);

    public function isEmpty(): bool;
}
