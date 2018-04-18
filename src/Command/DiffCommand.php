<?php

namespace Ttskch\EsaCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ttskch\EsaCli\Esa\Proxy;

class DiffCommand extends Command
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
            ->setName('diff')
            ->setDescription('Print diff url for the post')
            ->addArgument('post-id', InputArgument::REQUIRED, 'Post id')
            ->addArgument('compare-revision', InputArgument::REQUIRED, 'Revision number compare to')
            ->addArgument('base-revision', InputArgument::OPTIONAL, 'Revision number of base')
            ->addOption('style-html', 's', InputOption::VALUE_NONE, 'Set this flag if you want to get html_diff url')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $postId = $input->getArgument('post-id');
        $compare = $input->getArgument('compare-revision');
        $base = $input->getArgument('base-revision');
        $styleHtml = $input->getOption('style-html');

        if (is_null($base)) {
            $base = $this->esaProxy->getPost($postId)['revision_number'];
        }

        $output->writeln(sprintf('https://quartetcom.esa.io/posts/%d/revisions/compare/%d...%d/%sdiff', $postId, $compare, $base, $styleHtml ? 'html_' : ''));
    }
}
