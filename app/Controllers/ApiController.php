<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\Redis;
use Repositories\CoasterRepository;
use Repositories\CoasterRepositoryInterface;
use Repositories\WagonRepository;
use Repositories\WagonRepositoryInterface;

final class ApiController extends BaseController
{
    private CoasterRepositoryInterface $coasterRepository;

    private WagonRepositoryInterface $wagonRepository;
    public function __construct()
    {
//        $this->coasterRepository = new CoasterRepository();
//        $this->wagonRepository = new WagonRepository();
    }

    public function get(): string
    {
        $test = new Redis();
        dd($test);
        $redis = service('cache');
        $redis->save('test_key', 'Wartość w Redis', 3600);

        dd($redis->get('test_key'));
    }
}