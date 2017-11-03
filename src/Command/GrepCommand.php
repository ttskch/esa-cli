<?php

namespace Ttskch\EsaCli\Command;

use Doctrine\Common\Cache\Cache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ttskch\EsaCli\Exception\AuthenticationRequirementException;

class GrepCommand extends Command
{
    const CACHE_KEY = '';

    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    protected function configure(): void
    {
        $this
            ->setName('grep')
            ->setDescription('Do grep across multiple posts.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if (!$auth = $this->cache->fetch(AuthenticateCommand::CACHE_KEY)) {
            throw new AuthenticationRequirementException();
        }

        // todo
    }
}
