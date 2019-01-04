<?php

namespace app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\bandeira\BandeiraRouter as BandeiraRouter;
class Router {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->root();
        $this->bandeira();
    }

    private function root() {
        $this->app->group('/', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                return $response->withJson(['message'=> 'Hello World'], 200);
            });
        });
    }

    private function bandeira() {
        $bandeiraRouter = new BandeiraRouter($this->app);
    }

} 