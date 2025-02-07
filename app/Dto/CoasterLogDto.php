<?php
declare(strict_types=1);

namespace App\Dto;

final class CoasterLogDto extends BaseDto
{
    protected array $serialized = [
        'id', 'from', 'to', 'wagons', 'wagonsNeeded', 'workers', 'clients',
    ];
    protected string $from;
    protected string $to;
    protected int $wagons;
    protected int $wagonsNeeded = 0;
    protected int $workers;
    protected int $clients;
    public function __construct(
        int $id
    ) {
        $this->id = $id;
    }

    public function set(string $key, mixed $value): self
    {
        if (in_array($key, $this->serialized, true)) {
            $this->$key = $value;
        }

        return $this;
    }

    public function getWorkersNeeded(): int
    {
        return $this->wagonsNeeded * 2 + 1;
    }

    public function __toString(): string
    {
        $log = 'Kolejka ID ' . $this->id . ' - ';
        $problems = [];
        $wagonsCount = $this->wagons - $this->wagonsNeeded;
        $workersCount = $this->workers - $this->getWorkersNeeded();
        if ($this->wagons > 0) {
            if ($wagonsCount > 0) {
                $problems[] = 'Za dużo wagonów o: ' . abs($wagonsCount);
            } elseif ($wagonsCount < 0) {
                $problems[] = 'Za mało wagonów o: ' . abs($wagonsCount);
            }

            if ($workersCount > 0) {
                $problems[] = 'Za dużo pracowników o: ' . abs($workersCount);
            } elseif ($workersCount < 0) {
                $problems[] = 'Za mało pracowników o: ' . abs($workersCount);
            }
        } else {
            $problems[] = 'Brak wagonów (nie można wyliczyć pracowników)';
        }

        if (empty($problems)) {
            return $log . 'OK';
        }

        return $log . ' Problem: ' . implode(' ', $problems);
    }
}
