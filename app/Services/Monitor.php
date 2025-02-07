<?php
declare(strict_types=1);

namespace App\Services;

use CodeIgniter\Log\Logger;
use WebSocket\Client;
use WebSocket\ConnectionException;
use WebSocket\EchoLog;
use WebSocket\Server;

final class Monitor
{
    private static ?self $instance = null;

    private ?int $port = null;

    private Redis $redis;
    private Logger $logger;

    const PORT_REDIS_KEY = '-monitor-port';

    private function __construct()
    {
        $this->redis = new Redis();
        $this->logger = new Logger(new \Config\Logger());
        $this->port = (int)$this->redis->get(getenv('CI_ENVIRONMENT') . self::PORT_REDIS_KEY);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function start(): void
    {
        set_time_limit(0);
        ini_set('default_socket_timeout', -1);

        $options = array_merge([
            'port'          => 8000,
            'timeout'       => 200,
            'filter'        => ['text'],
        ], getopt('', ['port:', 'timeout:', 'debug']));

        try {
            $server = new Server($options);
            $this->redis->set(getenv('CI_ENVIRONMENT') . self::PORT_REDIS_KEY, (string)$server->getPort());
            system('clear');
        } catch (ConnectionException $e) {
            echo "> ERROR: {$e->getMessage()}\n";
            die();
        }

        while (true) {
            try {
                while ($server->accept()) {
                    while (true) {
                        $message = $server->receive();
                        if ($message !== null && $message !== 'ttfn') {
                            $this->displayMessage($message);
                        }
                    }
                }
            } catch (ConnectionException $e) {
                $this->logger->warning($e->getMessage());
            }
        }
    }

    public function send(string $message): void
    {
        $options = array_merge([
            'uri'           => 'ws://localhost:' . $this->port,
            'opcode'        => 'text',
            'port'          => $this->port,
        ], getopt('', ['uri:', 'opcode:', 'debug', 'port']));

        if (isset($options['debug']) && class_exists('WebSocket\EchoLog')) {
            $logger = new EchoLog();
            $options['logger'] = $logger;
        }

        if ($this->port !== null) {
            try {
                $client = new Client($options['uri'], $options);
                $client->send($message);
                $client->close();
            } catch (\Throwable $e) {
                $this->logger->warning($e->getMessage());
            }
        }
    }

    private function displayMessage(string $message): void
    {
        $array = json_decode($message, true);
        $array = array_map(function ($item) {
            return json_decode($item);
        }, $array);
        $result = [];
        foreach ($array as $item) {
            foreach ($item as $key => $value) {
                if (!isset($result[$key])) {
                    $result[$key] = $value;
                    continue;
                }
                $result[$key] = array_merge($result[$key], $value);
            }
        }
        system('clear');
        foreach ($result as $validatorKey => $validatorMessages) {
            echo $validatorKey . "\n-----------------------------------\n";
            foreach ($validatorMessages as $validatorMessage) {
                echo ' - ' . $validatorMessage . "\n";
            }
        }
    }
}
