<?php

if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=omlet2',
        'username' => 'admin',
        'password' => 'admin',
        'charset' => 'utf8',
    ];
}
// production db connection
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
];

