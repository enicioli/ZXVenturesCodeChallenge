<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Igorw\Silex\ConfigServiceProvider;
use Knp\Provider\ConsoleServiceProvider;

define('ROOT_PATH', __DIR__ . '/..');
define('CONFIG_FILE', __DIR__ . '/../resources/config/config.yml');
date_default_timezone_set('America/Sao_Paulo');

/** @var $app Silex\Application */
$app = new Silex\Application();

$app->register(new ConfigServiceProvider(CONFIG_FILE));
$app->register(new ConsoleServiceProvider());

require 'logger.php';
require 'database.php';
require 'services.php';

return $app;
