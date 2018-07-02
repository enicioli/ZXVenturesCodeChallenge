<?php

namespace ZXVentures\CodeChallenge\Services;

use Silex\Application;
use Monolog\Logger;
use Doctrine\ODM\MongoDB\DocumentManager;
use ZXVentures\CodeChallenge\ODM\Documents\PDV;
use ZXVentures\CodeChallenge\ODM\Repositories\PDVRepository;

/**
 * Class AbstractService
 *
 * @package ZXVentures\CodeChallenge\Services
 */
abstract class AbstractService
{
    /** @var Application */
    protected $app;

    /** @var Logger $logger */
    protected $logger;

    /** @var DocumentManager */
    protected $dm;

    /**
     * BaseService constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->logger = $this->app['logger'];
        $this->dm = $this->app['mongodbodm.dm'];
    }

    /**
     * @return PDVRepository
     */
    protected function getPDVRepository()
    {
        return $this->dm->getRepository(PDV::class);
    }
}
