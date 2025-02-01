<?php
declare(strict_types=1);

namespace App\Dto;

final class WagonDto extends BaseDto
{
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