<?php

use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Polidog\Esa\Client;
use Symfony\Component\Console\Application;
use Ttskch\EsaCli\Command\GrepCommand;
use Ttskch\EsaCli\Esa\Proxy;
use Ttskch\EsaCli\EsaCli;

$container = new Container();

require_once __DIR__ . '/parameters.php';

$container['console'] = function($container) {
    $console = new Application('ttskch/esa-cli');
    $console->add($container['grep_command']);

    return $console;
};

$container['cache'] = function() {
    return new FilesystemCache(__DIR__ . '/../var/cache');
};

$container['esa_cli'] = function($container) {
    return new EsaCli($container['console']);
};

$container['grep_command'] = function($container) {
    return new GrepCommand($container['esa_proxy']);
};

$container['esa_proxy'] = function($container) {
    return new Proxy($container['esa'], $container['cache'], $container['parameters.esa_paging_limit']);
};

$container['esa'] = function($container) {
    return new Client($container['parameters.access_token'], $container['parameters.team_name']);
};

return $container;
