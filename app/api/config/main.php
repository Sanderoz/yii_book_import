<?php

use yii\filters\auth\HttpBearerAuth;
use yii\web\JsonParser;
use yii\web\Response;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'format' => Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => \api\models\User::class,
            'enableAutoLogin' => false,
            'enableSession' => false
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'GET books/<isbn:[\w\-]+>' => 'books/view',
                'POST cart/add/<isbn:[\w\-]+>' => 'cart/add',
                'POST cart/minus/<isbn:[\w\-]+>' => 'cart/minus',
                'uploads/<filename:[\w\-]+>' => 'common/uploads/<filename>',
            ],
        ]
    ],
    'as beforeRequest' => [
        'class' => \yii\filters\ContentNegotiator::class,
        'formats' => [
            'application/json' => Response::FORMAT_JSON,
        ],
    ],
    'as authenticator' => [
        'class' => HttpBearerAuth::class,
        'except' => [
            'auth/get-token',
            'auth/get-token-by-refresh',
            'books/index',
            'books/view',
        ],
    ],
    'params' => $params,
];
