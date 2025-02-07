<?php
declare(strict_types=1);

namespace App\Services;

//use WebSocket\Client;
//use WebSocket\EchoLog;

abstract class BaseValidator
{
    protected ?array $result = null;

    protected string $validatorName = '';
//    protected int $port = 8000;

    public function getResult(): ?array
    {
        return $this->result !== null
            ? [$this->validatorName => $this->result]
            : null
            ;
    }
}