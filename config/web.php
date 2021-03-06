<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'catchAll' => MAINTENANCE_MODE ? ['site/maintenance'] : null,
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'settings' => [
            'class' => 'yii\Settings',
            // optional configuration:
            'db' => 'db', // DB Component ID
            'preLoad' => ['general']
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'olJb-i3sNykbzJ-X-VMhQ-vp3anWfAfyjpX',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['cabinet/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'sitemap.xml' => 'site/sitemap',
                'search' => 'search/search',
                'recipes' => 'search/index',
                'recipes/<alias>' => 'search/category',
                'cabinet' => 'cabinet/kitchen',
                'add' => 'recipe/add',
                'edit/<alias>' => 'recipe/edit',
                'recipe/<alias>' => 'recipe/show',
                'cook/<email>' => 'cabinet/profile',
                '<controller>/<action>' => '<controller>/<action>',

            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
