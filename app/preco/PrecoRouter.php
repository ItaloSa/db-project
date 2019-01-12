<?php

namespace app\preco;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\preco\PrecoCtrl as PrecoCtrl;

class PrecoRouter{

	private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/precos', function () {
            $this->post('', function (Request $request, Response $response) {
                $precoCtrl = new PrecoCtrl();
                try {
                    $preco = $precoCtrl->create($request->getParsedBody());
                    return $response->withJson($preco->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/precos', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $precoCtrl = new PrecoCtrl();
                try {
                    $precos = $precoCtrl->getAll();
                    return $response->withJson($precos, 200);
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
        $this->app->group('/precos', function () {
            $this->put('/{momento}', function (Request $request, Response $response, array $args) {
                $precoCtrl = new PrecoCtrl();
                try {
                    $preco = $precoCtrl->update($args['momento'], $request->getParsedBody());
                    return $response->withJson($preco->json(), 200);
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
        $this->app->group('/precos', function () {
            $this->delete('/{momento}', function (Request $request, Response $response, array $args) {
                $precoCtrl = new PrecoCtrl();
                try {
                    $precoCtrl->delete($args['momento']);
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
