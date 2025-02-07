<?php
declare(strict_types=1);

namespace App\Commands;

use App\Services\Monitor;
use CodeIgniter\CLI\BaseCommand;

final class ValidationMonitor extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'validate:monitor';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Display validation information';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'validate:monitor [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params): void
    {
        Monitor::getInstance()->start();
    }
}
