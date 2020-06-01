<?php

namespace App\Module\WordsCounter\Service\WordsProvider;

interface StringsSourceInterface
{
    /**
     * @return array|string[]
     */
    public function getStrings(): array;
}
