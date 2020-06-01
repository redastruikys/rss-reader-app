<?php

namespace App\Common\Collection;

interface FilterableCollectionInterface
{
    public function filter($callable, int $flags = 0): void;
}
