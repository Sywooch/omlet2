<?php

if (in_array($_SERVER['HTTP_HOST'], ['omlet2', 'omlet.dev'])) {
    define('IS_DEV', true);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    define('IS_DEV', false);
}

define('DS', DIRECTORY_SEPARATOR);

// dump func
require __DIR__ . '/../service/dump-func.php';

define('HOST', $_SERVER['HTTP_HOST']);
define('PROTOCOL', $_SERVER['REQUEST_SCHEME']);
define('FULL_HOST', PROTOCOL . '://' . HOST);

// Redirects
if (strpos(HOST, 'www.') === 0) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . str_replace('www.', '', FULL_HOST));
}



require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');



(new yii\web\Application($config))->run();
