<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CoasterDto;
use App\Services\Redis;

final class CoasterRepository extends BaseRepository implements CoasterRepositoryInterface
{
    protected string $prefix = 'coaster-';

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $response = [];
        $coasters = $this->redis->getByPrefix($this->buildPrefix());
        foreach ($coasters as $coaster) {
            $response[] = CoasterDto::buildFromJSON($coaster);
        }

        return $response;
    }

    public function getById(int $id): ?CoasterDto
    {
        $response = $this->redis->get($this->buildPrefix() . $id);

        if (!$response) {
            return null;
        }

        return CoasterDto::buildFromJSON($response);
    }

    public function create(CoasterDto $coasterDto): CoasterDto
    {
        if ($coasterDto->getId() === null) {
            $coasterDto->setId( $this->redis->getNextId($this->buildPrefix()));
        }
        $this->redis->set($this->buildPrefix().$coasterDto->getId(), (string)$coasterDto);

        return $coasterDto;
    }

    public function update(CoasterDto $coasterDto): CoasterDto
    {
        $this->redis->set($this->buildPrefix().$coasterDto->getId(), (string)$coasterDto);

        return $coasterDto;
    }

    public function delete(int $id): bool
    {
        return $this->redis->remove($this->buildPrefix() . $id);
    }
}