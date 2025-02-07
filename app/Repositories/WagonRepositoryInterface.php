<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\WagonDto;

interface WagonRepositoryInterface
{
    /**
     * @return array|WagonDto[]
     */
    public function getAll(): array;
    public function getById(int $id) : ?WagonDto;

    public function create(WagonDto $wagonDto): WagonDto;

    public function update(WagonDto $wagonDto): WagonDto;

    public function delete(int $id): bool;

    /**
     * @return array|WagonDto[]
     */
    public function getByCoasterId(int $coasterId): array;

    public function deleteByCoastId(int $coasterId): int;
}