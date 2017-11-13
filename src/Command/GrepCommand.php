<?php

namespace Ttskch\EsaCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ttskch\EsaCli\Esa\Proxy;

class GrepCommand extends Command
{
    /**
     * @var Proxy
     */
    private $esaProxy;

    public function __construct(Proxy $esaProxy)
    {
        parent::__construct();

        $this->esaProxy = $esaProxy;
    }

    protected function configure(): void
    {
        $this
            ->setName('grep')
            ->setDescription('Do grep-like searching across multiple posts')
            ->addOption('query', 's', InputOption::VALUE_OPTIONAL, 'Query string to filter target posts (see https://docs.esa.io/posts/104)')
            ->addOption('regexp', 'e', InputOption::VALUE_NONE, 'Use regexp for pattern (see http://php.net/manual/ja/pcre.pattern.php)')
            ->addOption('ignore-case', 'i', InputOption::VALUE_NONE, 'Perform case insensitive matching')
            ->addOption('invert-match', 't', InputOption::VALUE_NONE, 'Select lines are not matched')
            ->addOption('line-number', 'l', InputOption::VALUE_NONE, 'Show line number')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forcely refresh cache of contents')
            ->addArgument('pattern', InputArgument::REQUIRED, 'Search pattern')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $query = $input->getOption('query');
        $posts = $this->esaProxy->getPosts($query, $input->getOption('force'));

        // convert pattern for preg_match
        $pattern = $input->getArgument('pattern');
        if ($input->getOption('regexp')) {
            $pattern = str_replace('\/', '/', $pattern);
            $pattern = str_replace('/', '\/', $pattern);
        } else {
            $pattern = preg_quote($pattern);
        }
        $pattern = sprintf('/%s/%s', $pattern, $input->getOption('ignore-case') ? 'i' : '');

        $matches = [];

        foreach ($posts as $post) {
            $fullName = sprintf('%s/%s', $post['category'], $post['name']);

            $i = 1;
            foreach (explode("\n", $post['body_md']) as $line) {
                $condition = preg_match($pattern, $line, $m);
                if ($input->getOption('invert-match')) {
                    $condition = !$condition;
                }

                if ($condition) {
                    $matches[] = [
                        'full_name' => $fullName,
                        'line_number' => $i,
                        'line' => $line,
                        'matched' => $m[0] ?? '',
                    ];
                }
                $i++;
            }
        }

        foreach ($matches as $match) {
            $fullName = $match['full_name'];
            $lineNumber = $input->getOption('line-number') ? $match['line_number'] . ':' : '';
            $line = str_replace($match['matched'], sprintf('<fg=red;options=bold>%s</>', $match['matched']), $match['line']);

            $output->writeln(sprintf('%s:%s%s', $fullName, $lineNumber, $line));
        }
    }
}
