<?php

namespace app\endereco;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\endereco\EnderecoCtrl as EnderecoCtrl;

class EnderecoRouter {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->post();
        $this->get();
        $this->update();
        $this->delete();
    }

    private function post() {
        $this->app->group('/cidades', function () {
            $this->post('', function (Request $request, Response $response) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidade = $enderecoCtrl->createCidade($request->getParsedBody());
                    return $response->withJson($cidade->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
        $this->app->group('/bairros', function () {
            $this->post('', function (Request $request, Response $response) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $bairro = $enderecoCtrl->createBairro($request->getParsedBody());
                    return $response->withJson($bairro->json(), 201);
                } catch (Exception $e) {
                    return $response->withJson(["Error" => $e->getMessage()], 400);
                }
            });
        });
    }

    private function get() {
        $this->app->group('/cidades', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidades = $enderecoCtrl->getAllCidades();
                    return $response->withJson($cidades, 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
            $this->get('/{nome}_{estado}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidade = $enderecoCtrl->getCidade($args['nome']);
                    return $response->withJson($cidade->json(), 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
        });
        $this->app->group('/bairros', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidades = $enderecoCtrl->getAllBairros();
                    return $response->withJson($cidades, 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
            $this->get('/{nome}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $bairro = $enderecoCtrl->getBairro($args['nome']);
                    return $response->withJson($bairro->json(), 200);
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
        $this->app->group('/cidades', function () {
            $this->put('/{nome}_{estado}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $cidade = $enderecoCtrl->updateCidade($args['nome'], $args['estado'], $request->getParsedBody());
                    return $response->withJson($cidade->json(), 200);
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        return $response->withJson(["Error" => $e->getMessage()], 404);
                    } else {
                        return $response->withJson(["Error" => $e->getMessage()], 400);
                    }
                }
            });
        });
        $this->app->group('/bairros', function () {
            $this->put('/{nome}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $bairro = $enderecoCtrl->updateBairro($args['nome'], $request->getParsedBody());
                    return $response->withJson($bairro->json(), 200);
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
        $this->app->group('/cidades', function () {
            $this->delete('/{nome}_{estado}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $endereco = $enderecoCtrl->deleteCidade($args['nome']);
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
        $this->app->group('/bairros', function () {
            $this->delete('/{nome}', function (Request $request, Response $response, array $args) {
                $enderecoCtrl = new EnderecoCtrl();
                try {
                    $endereco = $enderecoCtrl->deleteBairro($args['nome']);
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