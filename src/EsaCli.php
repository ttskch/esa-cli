<?php
namespace Ttskch\EsaCli;

use Symfony\Component\Console\Application;

class EsaCli
{
    /**
     * @var Application
     */
    private $console;

    public function __construct(Application $console)
    {
        $this->console = $console;
    }

    /**
     * @return Application
     */
    public function getConsole()
    {
        return $this->console;
    }
}
