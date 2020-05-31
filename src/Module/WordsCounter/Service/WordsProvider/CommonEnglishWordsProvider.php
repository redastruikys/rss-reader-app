<?php

namespace App\Module\WordsCounter\Service\WordsProvider;

use App\Module\WordsCounter\Model\Word;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommonEnglishWordsProvider implements WordsProviderInterface
{
    const CONFIG_KEY_NAME = 'common_words_list';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var WordsCollection
     */
    private $wordsCollection;

    /**
     * @var array|string[]
     */
    private $commonWordsList = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->wordsCollection = new WordsCollection();

        $this->loadConfig();
    }

    public function getWordsCollection(): WordsCollection
    {
        if ($this->wordsCollection->isEmpty()) {
            foreach ($this->commonWordsList as $value) {
                $this->wordsCollection->addItem(new Word($value));
            }
        }

        return $this->wordsCollection;
    }

    private function loadConfig(): void
    {
        $this->commonWordsList = self::getConfigValues($this->container);
    }

    /**
     * Helper function to get "common_words_list" values from "config/packages/common_words_list.yaml" config file
     *
     * @param ContainerInterface $container
     * @return array|string[]
     */
    public static function getConfigValues(ContainerInterface $container): array
    {
        if ($container->hasParameter(self::CONFIG_KEY_NAME)) {
            return (array)$container->getParameter(self::CONFIG_KEY_NAME);
        }

        return [];
    }
}
