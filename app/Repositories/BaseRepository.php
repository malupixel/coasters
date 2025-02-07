<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Services\Redis;

abstract class BaseRepository
{
    protected readonly Redis $redis;
    protected string $prefix = '';

    public function __construct()
    {
        $this->redis = new Redis();
    }

    protected function buildPrefix(): string
    {
        return getenv('CI_ENVIRONMENT') . '-' . $this->prefix;
    }

    protected function getNextId(): int
    {
        return $this->redis->getNextId($this->buildPrefix());
    }
}
