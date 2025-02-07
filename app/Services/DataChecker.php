<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CoasterRepository;
use App\Repositories\CoasterRepositoryInterface;
use App\Repositories\LogRepository;
use App\Repositories\LogRepositoryInterface;
use App\Repositories\WagonRepository;
use App\Repositories\WagonRepositoryInterface;
use CodeIgniter\Log\Logger;

final class DataChecker
{
    /**
     * @var array|ValidatorInterface[]
     */
    private array $validators = [];

    private readonly CoasterRepositoryInterface $coasterRepository;
    private readonly WagonRepositoryInterface $wagonRepository;

    private readonly LogRepositoryInterface $logRepository;

    public function __construct()
    {
        $this->coasterRepository = new CoasterRepository();
        $this->wagonRepository = new WagonRepository();
        $this->logRepository = new LogRepository();
    }
    public function addValidator(ValidatorInterface $validator): void
    {
        if (!in_array($validator, $this->validators, true)) {
            $this->validators[] = $validator;
        }
    }

    /**
     * @param array|string[] $validators
     * @return self
     */
    public static function init(array $validators = []): self
    {
        $checker = new self();

        foreach ($validators as $validator) {
            $checker->addValidator(new $validator);
        }

        return $checker;
    }

    public function check(int $coasterId): void
    {
        $coaster = $this->coasterRepository->getById($coasterId);
        $coaster->setWagons($this->wagonRepository->getByCoasterId($coasterId));

        $array = null;
        foreach ($this->validators as $validator) {
            $validator->validate($coaster);
            $result = $validator->getResult();
            if (!empty($result)) {
                $array = is_array($array) ? array_merge($array, $result) : $result;
            }
        }

        if (is_array($array) && !empty($array)) {
            try {
                $logger = new Logger(new \Config\Logger());
                array_walk($array, function (&$item) use ($logger) {
                    array_walk($item, function (&$record) use ($logger) {
                        $logger->info($record);
                    });
                });

                $msg = json_encode($array, JSON_THROW_ON_ERROR);
                $this->logRepository->create($coasterId, $msg);
                Monitor::getInstance()->send(
                    json_encode($this->logRepository->getAll())
                );
            } catch (\Exception $e) {
                $logger = new Logger(new \Config\Logger());
                $logger->warning($e->getMessage());
            }
        } else {
            try {
                $this->logRepository->delete($coasterId);
                Monitor::getInstance()->send(
                    json_encode($this->logRepository->getAll())
                );
            } catch (\Exception $e) {
                $logger = new Logger(new \Config\Logger());
                $logger->warning($e->getMessage());
            }
        }
    }
}
