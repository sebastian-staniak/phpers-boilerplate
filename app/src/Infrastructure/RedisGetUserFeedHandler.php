<?php
declare(strict_types = 1);

namespace Infrastructure;
use Application\GetUserFeed;

/**
 * Class RedisGetUserFeedHandler
 */
class RedisGetUserFeedHandler
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * RedisGetUserFeedHandler constructor.
     */
    public function __construct()
    {
        $this->redis = new \Redis();
        
        $this->redis->connect('redis.phpers.dev', 6379);
    }

    /**
     * @param GetUserFeed $query
     * @return bool|string
     */
    public function handle(GetUserFeed $query)
    {
        $feed = $this->redis->get("user_feed_{$query->getUser()->getUuid()->toString()}");

        if ($feed === false) {
            return [];
        }

        return $feed;
    }
}