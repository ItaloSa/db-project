<?php

namespace app\comentario;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\comentario\ComentarioCtrl as ComentarioCtrl;

class ComentarioRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/comentarios', function () {
            $this->post('', function (Request $request, Response $response) {
                $comentarioCtrl = new ComentarioCtrl();
                try {
                    $comentario = $comentarioCtrl->create($request->getParsedBody());
                    return $response->withJson($comentario->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/comentarios', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $comentarioCtrl = new ComentarioCtrl();
                try {
                    $comentarios = $comentarioCtrl->getAll();
                    return $response->withJson($comentarios, 200);
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
        $this->app->group('/comentarios', function () {
            $this->put('/{pessoa_login}', function (Request $request, Response $response, array $args) {
                $comentarioCtrl = new ComentarioCtrl();
                try {
                    $comentario = $comentarioCtrl->update($args['pessoa_login'], $request->getParsedBody());
                    return $response->withJson($comentario->json(), 200);
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
        $this->app->group('/comentarios', function () {
            $this->delete('/{pessoa_login}', function (Request $request, Response $response, array $args) {
                $comentarioCtrl = new ComentarioCtrl();
                try {
                    $comentarioCtrl->delete($args['pessoa_login']);
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