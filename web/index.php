<?php

error_reporting(E_ALL);
@ini_set('display_errors', 1);

//error_reporting(0);
//@ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

define('ROOT_PATH', __DIR__ . '/..');
define('CONFIG_FILE', __DIR__ . '/../resources/config/config.yml');

$app = new Silex\Application();

require __DIR__ . '/../src/app.php';

$app->run();
