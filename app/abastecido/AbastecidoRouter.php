<?php

namespace app\abastecido;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\abastecido\AbastecidoCtrl as AbastecidoCtrl;

class AbastecidoRouter{

	private $app;
    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/abastecidos', function () {
            $this->post('', function (Request $request, Response $response) {
                $abastecidoCtrl = new AbastecidoCtrl();
                try {
                    $abastecidos = $abastecidoCtrl->create($request->getParsedBody());
                    return $response->withJson($abastecidos->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/abastecidos', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $abastecidoCtrl = new AbastecidoCtrl();
                try {
                    $abastecidos = $abastecidoCtrl->getAll();
                    return $response->withJson($abastecidos, 200);
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
        $this->app->group('/abastecidos', function () {
            $this->put('/{combustivel_nome}_{veiculo_placa}', function (Request $request, Response $response, array $args) {
                $abastecidoCtrl = new AbastecidoCtrl();
                try {
                    $abastecido = $abastecidoCtrl->update($args['combustivel_nome'], $args['veiculo_placa'], $request->getParsedBody());
                    return $response->withJson($abastecido->json(), 200);
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
        $this->app->group('/abastecidos', function () {
            $this->delete('/{combustivel_nome}_{veiculo_placa}', function (Request $request, Response $response, array $args) {
                $abastecidoCtrl = new AbastecidoCtrl();
                try {
                    $abastecidoCtrl->delete($args['combustivel_nome'], $args['veiculo_placa']);
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