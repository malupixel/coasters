<?php
declare(strict_types=1);

namespace App\Dto;

use CodeIgniter\I18n\TimeDifference;

final class CoasterDto extends BaseDto
{
    protected array $updatable = [
        'workersCount', 'personsCount', 'from', 'to'
    ];

    protected array $serialized = [
        'id', 'workersCount', 'personsCount', 'length', 'from', 'to'
    ];

    /**
     * @var array|WagonDto[]
     */
    protected array $wagons = [];

    public function __construct(
        ?int $id,
        protected int $workersCount,
        protected int $personsCount,
        protected int $length,
        protected string $from,
        protected string $to
    ) {
        $this->id = $id;
    }

    public function getWorkersCount(): int
    {
        return $this->workersCount;
    }

    public function getPersonsCount(): int
    {
        return $this->personsCount;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getOpenDuration(): int
    {
        try {
            $operationTime = (new TimeDifference(new \DateTime($this->from), new \DateTime($this->to)))->getSeconds();
        } catch (\Exception $e) {
            $operationTime = 0;
        }
        return $operationTime;
    }

    public function getNumberOfWagons(): int
    {
        return count($this->wagons);
    }

    /**
     * @return array|WagonDto[]
     */
    public function getWagons(): array
    {
        return $this->wagons;
    }

    /**
     * @param array|WagonDto[] $wagons
     * @return void
     */
    public function setWagons(array $wagons): void
    {
        $this->wagons = $wagons;
    }

    public function withWagons(): self
    {
        return $this;
    }

    public static function buildFromJSON(string $json): CoasterDto
    {
        $data = json_decode($json, true);

        return new CoasterDto(
            $data['id'] ?? null,
            $data['workersCount'] ?? 0,
            $data['personsCount'] ?? 0,
            $data['length'] ?? 0,
            $data['from'] ?? '',
            $data['to'] ?? ''
        );
    }
}