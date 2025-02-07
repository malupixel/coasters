<?php
declare(strict_types=1);

namespace App\Services;

use App\Dto\CoasterDto;

final class WorkersValidator extends BaseValidator implements ValidatorInterface
{
    protected string $validatorName = 'workers';

    public function validate(CoasterDto $coasterDto): void
    {
        $workersCount = $coasterDto->getWorkersCount() - ($coasterDto->getNumberOfWagons() * 2 + 1);
        if ($workersCount !== 0) {
            $this->result = $workersCount > 0
                ? ['Kolejka ID ' . $coasterDto->getId() . ': Liczba nadmiarowych pracowników - ' . abs($workersCount)]
                : ['Kolejka ID ' . $coasterDto->getId() . ': Liczba brakujacych pracownikow - ' . abs($workersCount)];
                ;
        }
    }
}