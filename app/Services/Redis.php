<?php
declare(strict_types=1);

namespace App\Services;
final class Redis
{
    private \Redis $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('172.63.0.6', 6379);
    }

    public function get(string $key): ?string
    {
        $response = $this->redis->get($key);
        if (!$response) {
            return null;
        }

        return $response;
    }

    public function set(string $key, string $value): void
    {
        $this->redis->set($key, $value, null);
    }

    public function getByPrefix(string $prefix): array
    {
        $keys =  $this->redis->scan($i, $prefix.'*');
        if ($keys !== false) {
            $coasters = $this->redis->mget($keys);
            if (is_array($coasters)) {
                return $coasters;
            }
        }

        return [];
    }

    public function remove(string $key): bool
    {
        $result = $this->redis->del($key);

        return $result !== false;
    }

    public function getNextId(string $prefix): int
    {
        $keys =  $this->redis->scan($i, $prefix.'*');
        $ids = array_map(function ($key) {
            return explode('-', $key)[2];
        }, $keys);

        if (empty($ids)) {
            return 1;
        }

        return max($ids) + 1;
    }
}