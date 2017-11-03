<?php

use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Question\Question;
use Ttskch\EsaCli\Command\AuthenticateCommand;
use Ttskch\EsaCli\Command\GrepCommand;
use Ttskch\EsaCli\EsaCli;

$container = new Container();

$container['console'] = function($container) {
    $console = new Application('ttskch/esa-cli');
    $console->add($container['auth_command']);
    $console->add($container['grep_command']);

    return $console;
};

$container['cache'] = function() {
    return new FilesystemCache(__DIR__ . '/../var/cache');
};

$container['esa_cli'] = function($container) {
    return new EsaCli($container['console']);
};

$container['auth_command'] = function($container) {
    return new AuthenticateCommand($container['cache'], $container['team_name_question'], $container['access_token_question']);
};

$container['grep_command'] = function($container) {
    return new GrepCommand($container['cache']);
};

$container['team_name_question'] = function() {
    return new Question('Team name : ');
};

$container['access_token_question'] = function() {
    return new Question('Personal access token : ');
};

return $container;
