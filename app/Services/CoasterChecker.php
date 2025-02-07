<?php
declare(strict_types=1);

namespace App\Services;

use App\Dto\CoasterDto;
use App\Dto\CoasterLogDto;
use App\Dto\WagonDto;

final class CoasterChecker
{
    private CoasterDto $coasterDto;
    private CoasterLogDto $coasterLogDto;
    public function check(CoasterDto $coaster): CoasterLogDto
    {
        $this->coasterDto = $coaster;
        $this->coasterLogDto = new CoasterLogDto($coaster->getId());
        $this->coasterLogDto
            ->set('from', $coaster->getFrom())
            ->set('to', $coaster->getTo())
            ->set('workers', $coaster->getWorkersCount())
            ->set('clients', $coaster->getPersonsCount())
            ->set('wagons', $coaster->getNumberOfWagons())
            ;
        $possibleClientsNumber = $this->getPossibleClientsNumber();

        if ($possibleClientsNumber >= $coaster->getPersonsCount()
            && $possibleClientsNumber < $coaster->getPersonsCount() * 2
        ) {
            $this->coasterLogDto->set('wagonsNeeded', $coaster->getNumberOfWagons());
            return $this->coasterLogDto;
        }

        $wagonMaxClients = $coaster->getNumberOfWagons() === 0 ? 0 : $coaster->getWagons()[0]->getMaxDailyClients(
            trackLength: $coaster->getLength(),duration: $coaster->getOpenDuration()
        );

        if ($wagonMaxClients > 0) {
            $this->coasterLogDto->set('wagonsNeeded', (int)floor($coaster->getPersonsCount() / $wagonMaxClients) + 1);
        }

        return $this->coasterLogDto;
    }

    private function getPossibleClientsNumber(): int
    {
        $clientNumber = 0;
        foreach ($this->coasterDto->getWagons() as $wagon) {
            $clientNumber+= $wagon->getMaxDailyClients(
                $this->coasterDto->getLength(),
                $this->coasterDto->getOpenDuration()
            );
        }

        return $clientNumber;
    }
}
