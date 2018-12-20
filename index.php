<?php

require_once "vendor/autoload.php";

use app\Router as Router;

$app = new \Slim\App;

$router = new Router($app);

$app->run();