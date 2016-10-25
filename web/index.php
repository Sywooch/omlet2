<?php

if ($_SERVER['HTTP_HOST'] == 'omlet2') {
    define('IS_LOCAL', true);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    define('IS_LOCAL', false);
}
define('DS', DIRECTORY_SEPARATOR);


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');



(new yii\web\Application($config))->run();
