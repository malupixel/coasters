<?php
declare(strict_types=1);

namespace App\Services;

use App\Dto\CoasterDto;

final class CoastersValidator extends BaseValidator implements ValidatorInterface
{
    private CoasterChecker $coasterChecker;

    public function __construct()
    {
        $this->coasterChecker = new CoasterChecker();
    }

    protected string $validatorName = 'coaster';

    public function validate(CoasterDto $coasterDto): void
    {
        $coasterLogDto = $this->coasterChecker->check($coasterDto);
        $this->result = [(string)$coasterLogDto];
    }
}