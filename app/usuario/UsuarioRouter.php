<?php

namespace app\usuario;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\usuario\UsuarioCtrl as UsuarioCtrl;

class UsuarioRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/usuarios', function () {
            $this->post('', function (Request $request, Response $response) {
                $usuarioCtrl = new UsuarioCtrl();
                try {
                    $usuario = $usuarioCtrl->create($request->getParsedBody());
                    return $response->withJson($usuario->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/usuarios', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $usuarioCtrl = new UsuarioCtrl();
                try {
                    $usuarios = $usuarioCtrl->getAll();
                    return $response->withJson($usuarios, 200);
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
        $this->app->group('/usuarios', function () {
            $this->put('/{login}', function (Request $request, Response $response, array $args) {
                $usuarioCtrl = new UsuarioCtrl();
                try {
                    $usuario = $usuarioCtrl->update($args['login'], $request->getParsedBody());
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
        $this->app->group('/usuarios', function () {
            $this->delete('/{login}', function (Request $request, Response $response, array $args) {
                $usuarioCtrl = new UsuarioCtrl();
                try {
                    $usuarioCtrl->delete($args['login']);
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