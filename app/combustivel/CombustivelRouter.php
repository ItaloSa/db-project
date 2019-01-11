<?php

namespace app\combustivel;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\combustivel\CombustivelCtrl as CombustivelCtrl;

class CombustivelRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/combustiveis', function () {
            $this->post('', function (Request $request, Response $response) {
                $combustivelCtrl = new CombustivelCtrl();
                try {
                    $combustivel = $combustivelCtrl->create($request->getParsedBody());
                    return $response->withJson($combustivel->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

  

    private function get() {
        $this->app->group('/combustiveis', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $combustivelCtrl = new CombustivelCtrl();
                try {
                    $combustivel = $combustivelCtrl->getAll();
                    return $response->withJson($combustivel, 200);
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
        $this->app->group('/combustiveis', function () {
            $this->put('/{nome}', function (Request $request, Response $response, array $args) {
                $combustivelCtrl = new CombustivelCtrl();
                try {
                    $combustivel = $combustivelCtrl->update($args['nome'], $request->getParsedBody());
                    return $response->withJson($combustivel->json(), 200);
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
        $this->app->group('/combustiveis', function () {
            $this->delete('/{nome}', function (Request $request, Response $response, array $args) {
                $combustivelCtrl = new CombustivelCtrl();
                try {
                    $combustivelCtrl->delete($args['nome']);
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

