<?php
declare(strict_types=1);

namespace App\Repositories;

interface LogRepositoryInterface
{
    public function create(int $coasterId, string $data): void;

    public function getAll(): array;

    public function delete(int $coasterId): void;
}
