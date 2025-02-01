<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\WagonDto;

final class WagonRepository extends BaseRepository implements WagonRepositoryInterface
{
    protected string $prefix = 'wagon-';

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $response = [];
        $wagons = $this->redis->getByPrefix($this->buildPrefix());
        foreach ($wagons as $wagon) {
            $response[] = WagonDto::buildFromJSON($wagon);
        }

        return $response;
    }

    public function getById(int $id): ?WagonDto
    {
        $response = $this->redis->get($this->buildPrefix() . $id);

        if (!$response) {
            return null;
        }

        return WagonDto::buildFromJSON($response);
    }

    public function create(WagonDto $wagonDto): WagonDto
    {
        if ($wagonDto->getId() === null) {
            $wagonDto->setId( $this->redis->getNextId($this->buildPrefix()));
        }
        $this->redis->set($this->buildPrefix().$wagonDto->getId(), (string)$wagonDto);

        return $wagonDto;
    }

    public function update(WagonDto $wagonDto): WagonDto
    {
        $this->redis->set($this->buildPrefix().$wagonDto->getId(), (string)$wagonDto);

        return $wagonDto;
    }

    public function delete(int $id): bool
    {
        return $this->redis->remove($this->buildPrefix() . $id);
    }

    public function deleteByCoastId(int $coastId): int
    {
        $removed = 0;
        $wagons = $this->getAll();
        foreach ($wagons as $wagon) {
            if ($wagon->getCoasterId() === $coastId) {
                if ($this->delete($wagon->getId())) {
                    $removed++;
                }
            }
        }

        return $removed;
    }
}