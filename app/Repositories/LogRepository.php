<?php
declare(strict_types=1);

namespace App\Repositories;

final class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    protected string $prefix = 'monitorlog-';

    public function create(int $coasterId, string $data): void
    {
        $this->redis->set($this->buildPrefix().$coasterId, $data);
    }

    /**
     * @return array|string[]
     */
    public function getAll(): array
    {
        return $this->redis->getByPrefix($this->buildPrefix());
    }

    public function delete(int $coasterId): void
    {
        $this->redis->remove($this->buildPrefix().$coasterId);
    }
}
