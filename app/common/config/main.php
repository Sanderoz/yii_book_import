<?php
return [
    'name' => 'Book library',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
//            'dsn' => 'mysql:host=127.0.0.1;dbname=yii_db;',
            'dsn' => 'mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DATABASE'].';port='.$_ENV['MYSQL_PORT'],
            'username' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'charset' => 'utf8',
        ],
        'queue' => [
            'class' => 'yii\queue\amqp_interop\Queue',
            'driver' => 'enqueue/amqp-lib',
            'host' => $_ENV['RABBITMQ_HOST'],
            'port' => $_ENV['RABBITMQ_PORT'],
            'user' => $_ENV['RABBITMQ_USER'],
            'password' => $_ENV['RABBITMQ_PASSWORD'],
            'queueName' => 'my_queue',
            'as log' => 'yii\queue\LogBehavior',
        ],
    ],
];
