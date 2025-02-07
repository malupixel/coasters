<?php
declare(strict_types=1);

namespace App\Dto;

final class LogDto extends BaseDto
{
    /**
     * @var array|CoasterLogDto[]
     */
    protected array $coasters = [];

    /**
     * @return array|CoasterLogDto[]
     */
    public function getCoasters(): array
    {
        return $this->coasters;
    }

    public function addCoaster(CoasterLogDto $coaster): void
    {
        if (in_array($coaster, $this->coasters, true)) {
            $this->coasters[] = $coaster;
        }
    }

    public function toArray(): array
    {
        return array_map(static function (CoasterLogDto $coaster) {
            return $coaster->toArray();
        }, $this->coasters);
    }
}
