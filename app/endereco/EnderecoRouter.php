<?php

namespace app\endereco;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\endereco\EnderecoCtrl as EnderecoCtrl;

class EnderecoRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
    }

    private function post() {
        $this->app->group('/cidades', function () {
            $this->post('', function (Request $request, Response $response) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidade = $enderecoCtrl->createCidade($request->getParsedBody());
                    return $response->withJson($cidade->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }
}