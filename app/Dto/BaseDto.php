<?php
declare(strict_types=1);

namespace App\Dto;

abstract class BaseDto
{
    protected array $updatable = [];
    protected array $serialized = [];
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        if ($this->id === null && $id > 0) {
            $this->id = $id;
        }
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->serialized as $key) {
            $array[$key] = $this->$key;
        }

        return $array;
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    public function update(array $data): void
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->updatable, true)) {
                $this->$key = $value;
            }
        }
    }
}