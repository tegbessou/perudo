<?php

namespace App\Repository;

use Snc\RedisBundle\Client\Phpredis\Client;

class AbstractRepository implements RepositoryInterface
{
    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function exist(string $uuid): bool
    {
        return $this->redis->exists($uuid);
    }

    public function find(string $uuid): string
    {
        return $this->redis->get($uuid);
    }

    public function save(string $uuid, int $ttl, string $data): void
    {
        $this->redis->setex($uuid, $ttl, $data);
    }
}
