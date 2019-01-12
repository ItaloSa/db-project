<?php

namespace app\bandeira;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\bandeira\BandeiraCtrl as BandeiraCtrl;

class BandeiraRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/bandeiras', function () {
            $this->post('', function (Request $request, Response $response) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeira = $bandeiraCtrl->create($request->getParsedBody());
                    return $response->withJson($bandeira->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/bandeiras', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeiras = $bandeiraCtrl->getAll();
                    return $response->withJson($bandeiras, 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
            $this->get('/{nome}', function (Request $request, Response $response, array $args) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeira = $bandeiraCtrl->get($args['nome']);
                    return $response->withJson($bandeira->json(), 200);
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
        $this->app->group('/bandeiras', function () {
            $this->put('/{nome}', function (Request $request, Response $response, array $args) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeira = $bandeiraCtrl->update($args['nome'], $request->getParsedBody());
                    return $response->withJson($bandeira->json(), 200);
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
        $this->app->group('/bandeiras', function () {
            $this->delete('/{nome}', function (Request $request, Response $response, array $args) {
                $bandeiraCtrl = new BandeiraCtrl();
                try {
                    $bandeira = $bandeiraCtrl->delete($args['nome']);
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