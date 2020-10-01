<?php

if (version_compare(PHP_VERSION, '7.3.1') === -1) {
    $version = explode('-', PHP_VERSION);
    echo 'This version of BabiPHP requires at least PHP 7.3.1. ';
    echo 'You are currently running ' . $version[0] . '. Please update your PHP version.';
    return;
}

define('HORIZOM_START', microtime(true));
define('HORIZOM_ROOT', dirname(dirname(__FILE__)));
define('HORIZOM_WEBROOT', '/public/');
define('HORIZOM_PUBLIC', HORIZOM_ROOT . HORIZOM_WEBROOT);
define('HORIZOM_APP', HORIZOM_ROOT . '/app/');
define('HORIZOM_CONFIG', HORIZOM_ROOT . '/config/');
define('HORIZOM_RESOURCES', HORIZOM_ROOT . '/resources/');
define('HORIZOM_APP_NAMESPACE', 'App');

require HORIZOM_ROOT . '/vendor/autoload.php';

$appConfig = (array)require(HORIZOM_CONFIG . 'app.php');
$authConfig = (array)require(HORIZOM_CONFIG . 'auth.php');
$databaseConfig = (array)require(HORIZOM_CONFIG . 'database.php');
$settings = array_merge($appConfig, $authConfig, $databaseConfig);

$app = new Horizom\Core\App($settings);

require __DIR__ . '/dependencies.php';
