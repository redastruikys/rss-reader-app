<?php

namespace App\Common\Collection;

interface WalkableCollectionInterface
{
    public function walk($callable): void;
}
