<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "vendor/autoload.php";

use app\Router as Router;
use Dotenv\Dotenv as Dotenv;

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