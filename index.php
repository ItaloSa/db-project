<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "vendor/autoload.php";

use app\Router as Router;
use Dotenv\Dotenv as Dotenv;
use Monolog\Logger as Logger;
use Monolog\Registry as Registry;
use Monolog\Handler\StreamHandler as StreamHandler;

$apiLog = new Logger('log');
$apiLog->pushHandler(new StreamHandler('./app.log', Logger::WARNING));
Registry::addLogger($apiLog);

$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

$settings = [];
if (getenv('ENV') != 'production') {
    $settings = [
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ];
}

$app = new \Slim\App($settings);

$router = new Router($app);

$app->run();