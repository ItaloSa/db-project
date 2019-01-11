<?php

namespace app\posto;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\posto\PostoCtrl as PostoCtrl;

class PostoRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/postos', function () {
            $this->post('', function (Request $request, Response $response) {
                $postoCtrl = new PostoCtrl();
                try {
                    $posto = $postoCtrl->create($request->getParsedBody());
                    return $response->withJson($posto->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/postos', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $postoCtrl = new PostoCtrl();
                try {
                    $postos = $postoCtrl->getAll();
                    return $response->withJson($postos, 200);
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
        $this->app->group('/postos', function () {
            $this->put('/{cnpj}', function (Request $request, Response $response, array $args) {
                $postoCtrl = new PostoCtrl();
                try {
                    $posto = $postoCtrl->update($args['cnpj'], $request->getParsedBody());
                    return $response->withJson($posto->json(), 200);
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
        $this->app->group('/postos', function () {
            $this->delete('/{cnpj}', function (Request $request, Response $response, array $args) {
                $postoCtrl = new PostoCtrl();
                try {
                    $postoCtrl->delete($args['cnpj']);
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