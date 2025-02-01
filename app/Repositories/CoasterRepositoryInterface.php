<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CoasterDto;

interface CoasterRepositoryInterface
{
    /**
     * @return array|CoasterDto[]
     */
    public function getAll(): array;

    public function getById(int $id): ?CoasterDto;

    public function create(CoasterDto $coasterDto): CoasterDto;

    public function update(CoasterDto $coasterDto): CoasterDto;

    public function delete(int $id): bool;
}