<?php

use Saxulum\DoctrineMongoDb\Provider\DoctrineMongoDbProvider;
use Saxulum\DoctrineMongoDbOdm\Provider\DoctrineMongoDbOdmProvider;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

/**
 * @var $app Silex\Application
 */

$app->register(new DoctrineMongoDbProvider, [
    'mongodb.options' => [
        'server' => $app['config']['db']['server'],
        'options' => [
            'username' => $app['config']['db']['user'],
            'password' => $app['config']['db']['password'],
            'db' => $app['config']['db']['database']
        ],
    ],
]);

$app->register(new DoctrineMongoDbOdmProvider, [
    'mongodbodm.proxies_dir' => '/var/www/storage/cache/doctrine',
    'mongodbodm.hydrator_dir' => '/var/www/storage/cache/doctrine',
    'mongodbodm.dm.options' => [
        'database' => $app['config']['db']['database'],
        'mappings' => [
            [
                'type' => 'annotation',
                'namespace' => 'ZXVentures\CodeChallenge\ODM\Documents',
                'path' => 'src/ZXVentures/CodeChallenge/ODM/Documents'
            ]
        ],
    ],
]);

AnnotationDriver::registerAnnotationClasses();
