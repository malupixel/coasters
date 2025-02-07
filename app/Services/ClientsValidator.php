<?php
declare(strict_types=1);

namespace App\Services;

use App\Dto\CoasterDto;

final class ClientsValidator extends BaseValidator implements ValidatorInterface
{
    protected string $validatorName = 'clients';

    public function validate(CoasterDto $coasterDto): void
    {
        // TODO: Implement validate() method.
    }
}