<?php
namespace Ttskch\EsaCli\Command;

use Doctrine\Common\Cache\Cache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AuthenticateCommand extends Command
{
    const CACHE_KEY = 'ttskch.esa_cli.auth';

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var Question
     */
    private $teamNameQuestion;

    /**
     * @var Question
     */
    private $accessTokenQuestion;

    public function __construct(Cache $cache, Question $teamNameQuestion, Question $accessTokenQuestion)
    {
        parent::__construct();

        $this->cache = $cache;
        $this->teamNameQuestion = $teamNameQuestion;
        $this->accessTokenQuestion = $accessTokenQuestion;
    }

    protected function configure(): void
    {
        $this
            ->setName('authenticate')
            ->setDescription('Authenticate as esa api client.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $question */
        $question = $this->getHelper('question');

        $teamName = $question->ask($input, $output, $this->teamNameQuestion);
        $accessToken = $question->ask($input, $output, $this->accessTokenQuestion);

        $this->cache->save(self::CACHE_KEY, [
            'team_name' => $teamName,
            'access_token' => $accessToken,
        ]);

        $output->writeln('authentication config is saved.');
    }
}
