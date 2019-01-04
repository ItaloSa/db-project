<?php

namespace app\bandeira;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\bandeira\BandeiraCtrl as BandeiraCtrl;

class BandeiraRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
    }

    private function post() {
        $this->app->group('/bandeira', function () {
            $this->post('', function (Request $request, Response $response) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeira = $bandeiraCtrl->create($request->getParsedBody());
                    return $response->withJson($bandeira->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/bandeira', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                return $response->withJson(['message'=> 'Hello Bandeira'], 200);
            });
        });
    }

} 