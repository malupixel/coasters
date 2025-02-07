<?php
declare(strict_types=1);

namespace App\Dto;

final class WagonDto extends BaseDto
{
    const WAGON_BREAK_DURATION = 300;

    protected array $updatable = ['places', 'speed'];
    protected array $serialized = ['id', 'coasterId', 'places', 'speed'];
    public function __construct(
        ?int $id,
        protected int $coasterId,
        protected int $places,
        protected float $speed
    ) {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoasterId(): int
    {
        return $this->coasterId;
    }

    public function getPlaces(): int
    {
        return $this->places;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function getMaxDailyClients(int $trackLength, int $duration): ?int
    {
        $courseTime = $trackLength / $this->speed;
        $maxCourses = floor($duration / $courseTime);
        while (($maxCourses * ($courseTime + self::WAGON_BREAK_DURATION)) - self::WAGON_BREAK_DURATION > $duration) {
            $maxCourses--;
        }

        return (int)$maxCourses * $this->places;
    }

    public static function buildFromJSON(string $json): self
    {
        $data = json_decode($json, true);
        return new self(
            id: $data['id'] ?? null,
            coasterId: $data['coasterId'] ?? 0,
            places: $data['places'] ?? 0,
            speed: $data['speed'] ?? 0.00,
        );
    }
}