<?php

namespace app\pessoa;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\pessoa\PessoaCtrl as PessoaCtrl;

class PessoaRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/pessoas', function () {
            $this->post('', function (Request $request, Response $response) {
                $pessoaCtrl = new PessoaCtrl();
                try {
                    $pessoa = $pessoaCtrl->create($request->getParsedBody());
                    return $response->withJson($pessoa->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

     private function get() {
        $this->app->group('/pessoas', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $pessoaCtrl = new PessoaCtrl();
                try {
                    $pessoa = $pessoaCtrl->getAll();
                    return $response->withJson($pessoa, 200);
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
        $this->app->group('/pessoas', function () {
            $this->put('/{login}', function (Request $request, Response $response, array $args) {
                $pessoaCtrl = new PessoaCtrl();
                try {
                    $pessoa = $pessoaCtrl->update($args['login'], $request->getParsedBody());
                    return $response->withJson($pessoa->json(), 200);
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
        $this->app->group('/pessoas', function () {
            $this->delete('/{login}', function (Request $request, Response $response, array $args) {
                $pessoaCtrl = new PessoaCtrl();
                try {
                    $pessoaCtrl->delete($args['login']);
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
