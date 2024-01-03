<?php
return [
    'name' => 'Book library',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['log', 'queue'],
    'components' => [
        'helper' => [
            'class' => \common\components\helpers\AppHelper::class,
        ],


        'cache' => [
            'class' => \yii\redis\Cache::class,
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
//            'dsn' => 'mysql:host=127.0.0.1;dbname=yii_db;',
            'dsn' => 'mysql:host=' . $_ENV['MYSQL_HOST'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';port=' . $_ENV['MYSQL_PORT'],
            'username' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'charset' => 'utf8',
        ],
        'queue' => [
            'class' => \yii\queue\amqp_interop\Queue::class,
            'driver' => 'enqueue/amqp-lib',
            'host' => $_ENV['RABBITMQ_HOST'],
            'port' => $_ENV['RABBITMQ_PORT'],
            'user' => $_ENV['RABBITMQ_USER'],
            'password' => $_ENV['RABBITMQ_PASSWORD'],
            'queueName' => 'import_queue',
            'as log' => 'yii\queue\LogBehavior',
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => $_ENV['REDIS_HOST'],
            'port' => $_ENV['REDIS_PORT'],
            'database' => $_ENV['REDIS_DATABASE'],
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            // You have to set
            //
            // 'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
            //    'transport' => [
            //        'scheme' => 'smtps',
            //        'host' => '',
            //        'username' => '',
            //        'password' => '',
            //        'port' => 465,
            //        'dsn' => 'native://default',
            //    ],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://user:pass@smtp.example.com:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
