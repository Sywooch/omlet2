<?php

if (IS_DEV) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=omlet2',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}
// production db connection
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhostlocalhost;dbname=omletkie_db_new',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
];

