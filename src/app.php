<?php

use Euskadi31\Silex\Provider\CorsServiceProvider;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;

date_default_timezone_set('America/Sao_Paulo');

/**
 * @var $app Silex\Application
 */

$app->register(new ConfigServiceProvider(CONFIG_FILE));
$app->register(new CorsServiceProvider());
$app->register(new HttpCacheServiceProvider(), $app['config']['cache']);

require 'logger.php';
require 'middleware.php';
require 'database.php';
require 'services.php';

return $app;
