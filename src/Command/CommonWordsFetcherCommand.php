<?php

namespace App\Command;

use App\Module\WordsCounter\Service\WordsProvider\CommonEnglishWordsProvider;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Yaml\Yaml;

class CommonWordsFetcherCommand extends Command
{
    const DEFAULT_WORDS_LIMIT = 50;
    const DEFAULT_COMBINE_MODE = false;
    const SOURCE_URL = 'https://en.wikipedia.org/wiki/Most_common_words_in_English';
    const REQUEST_METHOD = 'GET';
    const CSS_SELECTOR = '.wikitable > tbody > tr > td:first-child > a';

    protected static $defaultName = 'app:fetch_common_words';

    /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setDescription('Fetch top common words from Wikipedia')
            ->setHelp('This commands common_words_list config from words listed on Wikipedia page')
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Amount of words to pick',
                self::DEFAULT_WORDS_LIMIT
            )
            ->addOption(
                'combine',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Set to TRUE to combine words with current config, FALSE to clear current config',
                self::DEFAULT_COMBINE_MODE
            )
            ->addOption(
                'source-url',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Source page url',
                self::SOURCE_URL
            )
            ->addOption(
                'request-method',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Request method GET/POST',
                self::REQUEST_METHOD
            )
            ->addOption(
                'css-selector',
                's',
                InputOption::VALUE_OPTIONAL,
                'CSS selector to retrieve nodes',
                self::CSS_SELECTOR
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int)$input->getOption('limit');
        $sourceUrl = $input->getOption('source-url');
        $requestMethod = $input->getOption('request-method');
        $cssSelector = $input->getOption('css-selector');
        $combineMode = (bool)$input->getOption('combine');

        $output->writeln(sprintf('Fetching data from %s ...', $sourceUrl));

        $words = $this->fetch($limit, $requestMethod, $sourceUrl, $cssSelector);

        $this->storeConfig($words, $combineMode);

        $output->write('Result: ');
        $output->writeln(implode(', ', $words));

        return 0;
    }

    private function fetch(int $limit, string $requestMethod, string $sourceUrl, string $cssSelector): array {
        $browser = new HttpBrowser(HttpClient::create());

        $nodes = $browser
            ->request($requestMethod, $sourceUrl)
            ->filter($cssSelector)
            ->slice(0, $limit)
        ;

        $items = [];

        foreach ($nodes as $node) {
            $items[] = trim($node->textContent);
        }

        return $items;
    }

    /**
     * @param array $words
     * @param bool $combine
     */
    private function storeConfig(array $words, bool $combine): void
    {
        if ($combine) {
            $words = array_merge($words, $this->getCurrentConfigValues());
        }

        $words = array_unique($words);

        $data = [
            'parameters' => [
                CommonEnglishWordsProvider::CONFIG_KEY_NAME => $words
            ]
        ];

        $configFilename = __DIR__ . '/../../config/packages/' . CommonEnglishWordsProvider::CONFIG_KEY_NAME . '.yaml';

        file_put_contents($configFilename, Yaml::dump($data));
    }

    /**
     * @return array|string[]
     */
    private function getCurrentConfigValues(): array
    {
        return CommonEnglishWordsProvider::getConfigValues($this->container);
    }
}
