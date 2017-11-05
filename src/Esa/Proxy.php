<?php

namespace Ttskch\EsaCli\Esa;

use Doctrine\Common\Cache\Cache;
use Polidog\Esa\Client;
use Ttskch\EsaCli\Exception\LogicException;

class Proxy
{
    const CACHE_KEY_PREFIX = 'ttskch.esa_cli.esa.proxy';
    const CACHE_LIFETIME = 3600;

    /**
     * @var Client
     */
    private $esa;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var int
     */
    private $pageLimit;

    public function __construct(Client $esa, Cache $cache, int $pageLimit)
    {
        $this->esa = $esa;
        $this->cache = $cache;
        $this->pageLimit = $pageLimit;
    }

    /**
     * @param null|string $query
     * @return array
     */
    public function getPosts(?string $query): array
    {
        $cacheKey = self::CACHE_KEY_PREFIX . '.posts.' . hash('md5', $query);

        if (!$posts = $this->cache->fetch($cacheKey)) {
            $posts = $this->mergePages('posts', ['q' => $query]);
            $this->cache->save($cacheKey, $posts, self::CACHE_LIFETIME);
        }

        return $posts;
    }

    /**
     * @param string $methodName
     * @param array $params
     * @param int $firstPage
     * @return array
     */
    public function mergePages(string $methodName, array $params = [], int $firstPage = 1): array
    {
        if (!in_array($methodName, ['posts'])) {
            throw new LogicException('Invalid method name.');
        }

        $results = [];
        $page = $firstPage;

        do {
            $result = $this->esa->$methodName(array_merge($params, [
                'per_page' => 100,
                'page' => $page,
            ]));
            $results = array_merge($results, $result[$methodName]);
            $page = $result['next_page'];

        } while (!is_null($page));

        return $results;
    }
}