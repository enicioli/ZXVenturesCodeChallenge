<?php

use ZXVentures\CodeChallenge\RoutesLoader;
use ZXVentures\CodeChallenge\ServicesLoader;
use Silex\Provider\ServiceControllerServiceProvider;

/**
 * @var $app Silex\Application
 */

$app->register(new ServiceControllerServiceProvider());

/**
 * Load services
 */
$servicesLoader = new ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

/**
 * Load routes
 */
$routesLoader = new RoutesLoader($app);
$routesLoader->bindRoutesToControllers();
