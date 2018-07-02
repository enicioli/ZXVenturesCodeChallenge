<?php

namespace ZXVentures\CodeChallenge\Commands;

use Doctrine\ODM\MongoDB\DocumentManager;
use Knp\Command\Command;
use Monolog\Logger;
use Silex\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 * @package ZXVentures\CodeChallenge\Commands
 */
abstract class AbstractCommand extends Command
{
    /** @var Application|array */
    protected $app;

    /** @var Logger */
    protected $logger;

    /** @var DocumentManager */
    protected $dm;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public final function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getSilexApplication();
        $this->logger = $this->app['logger'];
        $this->dm = $this->app['mongodbodm.dm'];

        try {
            $this->_execute();
        } catch (\Exception $e) {
            $message = "Unexpected error in {$this->getName()}: {$e->getMessage()} Stack: {$e->getTraceAsString()}";
            $this->logger->error($message);
            echo $message;
        }
    }

    /**
     * @return void
     */
    protected function configure() : void
    {
        $this->setName((new \ReflectionClass($this))->getShortName());
        $this->setDescription($this->_getDescription());
    }

    /**
     * @throws \Exception
     */
    protected abstract function _execute();

    /**
     * @return string
     */
    protected abstract function _getDescription() : string;
}
