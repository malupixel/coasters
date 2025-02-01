<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Redis extends BaseConfig
{
    public string $host = '172.63.0.6';
    public int $port = 6379;
    public ?string $password = null;
    public int $timeout = 0;
    public int $database = 0;
}
