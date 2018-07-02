<?php

namespace ZXVentures\CodeChallenge;

use Monolog\Logger;
use Silex\Application;
use ZXVentures\CodeChallenge\Services\PDVService;

/**
 * Class ServicesLoader
 *
 * @package ZXVentures\CodeChallenge
 *
 * @property Application $app
 */
class ServicesLoader
{
    /** @var Application */
    protected $app;

    /** @var Logger */
    protected $logger;

    /**
     * ServicesLoader constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->logger = $this->app['logger'];
    }

    public function bindServicesIntoContainer()
    {
        $this->app['PDVService'] = function () {
            return new PDVService($this->app);
        };
    }
}
