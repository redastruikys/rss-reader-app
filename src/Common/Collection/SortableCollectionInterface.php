<?php

namespace App\Common\Collection;

interface SortableCollectionInterface
{
    public function sort($callable): void;
}
