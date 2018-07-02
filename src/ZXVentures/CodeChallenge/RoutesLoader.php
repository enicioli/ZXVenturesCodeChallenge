<?php

namespace ZXVentures\CodeChallenge;

use Monolog\Logger;
use Silex\Application;
use Silex\ControllerCollection;

/**
 * Class RoutesLoader
 *
 * @package ZXVentures\CodeChallenge
 *
 * @property Application $app
 */
class RoutesLoader
{
    /** @var Application */
    private $app;

    /** @var array */
    private $config;

    /** @var Logger */
    private $logger;

    /**
     * RoutesLoader constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $this->app['config'];
        $this->logger = $this->app['logger'];
        $this->instantiateControllers();
    }

    /**
     * @return void
     */
    public function bindRoutesToControllers() : void
    {
        /** @var ControllerCollection $api */
        $api = $this->app['controllers_factory'];

        $api->get('/health', 'health.controller:index');

        $api->post('/pdv', 'pdv.controller:create');
        $api->get('/pdv/covers', 'pdv.controller:searchByCoverageArea');
        $api->get('/pdv/{id}', 'pdv.controller:read');

        $this->app->mount('', $api);
    }

    /**
     * @return void
     */
    private function instantiateControllers() : void
    {
        $this->app['health.controller'] = function() {
            return new Controllers\Health\HealthController($this->app);
        };

        $this->app['pdv.controller'] = function() {
            return new Controllers\PDV\PDVController($this->app);
        };
    }
}
