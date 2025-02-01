<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CoasterRepository;
use App\Repositories\CoasterRepositoryInterface;
use App\Repositories\WagonRepository;
use App\Repositories\WagonRepositoryInterface;

final class WorkersValidator
{
    private readonly CoasterRepositoryInterface $coasterRepository;
    private readonly WagonRepositoryInterface $wagonRepository;

    public function __construct()
    {
        $this->coasterRepository = new CoasterRepository();
        $this->wagonRepository = new WagonRepository();
    }

    public function validate(): void
    {
        dd('VALIDATE');
    }
}