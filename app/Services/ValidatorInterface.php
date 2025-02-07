<?php
declare(strict_types=1);

namespace App\Services;

use App\Dto\CoasterDto;

interface ValidatorInterface
{
    public function validate(CoasterDto $coasterDto): void;

    public function getResult(): ?array;
}