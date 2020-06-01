<?php

namespace App\Common\Collection;

abstract class AbstractCollection implements
    CollectionInterface,
    WalkableCollectionInterface,
    FilterableCollectionInterface,
    SortableCollectionInterface
{
    private $position;

    private $items = [];

    public function __construct(array $items = [])
    {
        $this->position = 0;
        $this->setItems($items);
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    public function filter($callable, int $flags = 0): void
    {
        $this->items = array_filter($this->items, $callable, $flags);
    }

    public function walk($callable): void
    {
        array_walk($this->items, $callable);
    }

    public function sort($callable): void
    {
        usort($this->items, $callable);
    }
}
