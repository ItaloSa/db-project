<?php

namespace app\veiculo;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\veiculo\VeiculoCtrl as VeiculoCtrl;

class VeiculoRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/veiculos', function () {
            $this->post('', function (Request $request, Response $response) {
                $VeiculoCtrl = new VeiculoCtrl();
                try {
                    $usuario = $VeiculoCtrl->create($request->getParsedBody());
                    return $response->withJson($usuario->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/veiculos', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $veiculoCtrl = new VeiculoCtrl();
                try {
                    $veiculos = $veiculoCtrl->getAll();
                    return $response->withJson($veiculos, 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
        });
    }

    private function update() {
        $this->app->group('/veiculos', function () {
            $this->put('/{placa}', function (Request $request, Response $response, array $args) {
                $veiculoCtrl = new VeiculoCtrl();
                try {
                    $usuario = $veiculoCtrl->update($args['placa'], $request->getParsedBody());
                    return $response->withJson($usuario->json(), 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
        });
    }

    private function delete() {
        $this->app->group('/veiculos', function () {
            $this->delete('/{placa}', function (Request $request, Response $response, array $args) {
                $veiculoCtrl = new VeiculoCtrl();
                try {
                    $veiculoCtrl->delete($args['placa']);
                    return $response->withStatus(200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
        });
    }
}    