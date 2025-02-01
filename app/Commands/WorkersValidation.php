<?php
declare(strict_types=1);

namespace App\Commands;

use App\Services\WorkersValidator;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

final class WorkersValidation extends BaseCommand
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
    protected $name = 'validate:workers';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Validate number of workers';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

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
    public function run(array $params)
    {
        $workersValidator = new WorkersValidator();
        $workersValidator->validate();
    }
}
