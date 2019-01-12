<?php

namespace app\tipoUsuario;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\tipoUsuario\TipoUsuarioCtrl as TipoUsuarioCtrl;

class TipoUsuarioRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/tiposUsuario', function () {
            $this->post('', function (Request $request, Response $response) {
                $tipoUsuarioCtrl = new TipoUsuarioCtrl();
                try {
                    $tipoUsuario = $tipoUsuarioCtrl->create($request->getParsedBody());
                    return $response->withJson($tipoUsuario->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/tiposUsuario', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $tipoUsuarioCtrl = new TipoUsuarioCtrl();
                try {
                    $tiposUsuario = $tipoUsuarioCtrl->getAll();
                    return $response->withJson($tiposUsuario, 200);
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
        $this->app->group('/tiposUsuario', function () {
            $this->put('/{nome}', function (Request $request, Response $response, array $args) {
                $tipoUsuarioCtrl = new TipoUsuarioCtrl();
                try {
                    $tipoUsuario = $tipoUsuarioCtrl->update($args['nome'], $request->getParsedBody());
                    return $response->withJson($tipoUsuario->json(), 200);
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
        $this->app->group('/tiposUsuario', function () {
            $this->delete('/{nome}', function (Request $request, Response $response, array $args) {
                $tipoUsuarioCtrl = new TipoUsuarioCtrl();
                try {
                    $tipoUsuarioCtrl->delete($args['nome']);
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