<?php

use Silex\Provider\MonologServiceProvider;

/**
 * @var $app Silex\Application
 */

$app['debug'] = $app['config']['logger']['debug'];

$app->register(
    new MonologServiceProvider(),
    [
        'monolog.logfile' => $app['config']['logger']['log_file'],
        'monolog.level' => $app['config']['logger']['level']
    ]
);

$app['logger'] = function () use ($app) {
    $stream = new \Monolog\Handler\StreamHandler(
        $app['config']['logger']['log_file'],
        ($app['config']['logger']['debug']) ? \Monolog\Logger::DEBUG : \Monolog\Logger::INFO
    );

    $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    $requestId = $request->headers->get('X-Request-Id');
    $formatter = new \Monolog\Formatter\LineFormatter('{"level": "%level_name%", message":"%message%","requestId":"' . $requestId . '"}' . "\n");
    $stream->setFormatter($formatter);

    $logger = new \Monolog\Logger('application');
    $logger->pushHandler($stream);

    return $logger;
};
