<?php

namespace app;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Exception as Exception;

use app\bandeira\BandeiraRouter as BandeiraRouter;
use app\endereco\EnderecoRouter as EnderecoRouter;
use app\tipoUsuario\TipoUsuarioRouter as TipoUsuarioRouter;
use app\usuario\UsuarioRouter as UsuarioRouter;
use app\pessoa\PessoaRouter as PessoaRouter;
use app\veiculo\VeiculoRouter as VeiculoRouter;
use app\posto\PostoRouter as PostoRouter;

class Router {
    private $app;

    public function __construct(\Slim\App $app) {
        $this->app = $app;
        $this->root();
        $this->bandeira();
        $this->endereco();
        $this->tipoUsuario();
        $this->usuario();
        $this->pessoa();
        $this->veiculo();
        $this->posto();
    }

    private function root() {
        $this->app->group('/', function () {
            $this->get('', function (Request $request, Response $response, array $args) {
                return $response->withJson(['message'=> 'Hello World'], 200);
            });
        });
    }

    private function bandeira() {
        $bandeiraRouter = new BandeiraRouter($this->app);
    }

    private function endereco() {
        $enderecoRouter = new EnderecoRouter($this->app);
    }

    private function tipoUsuario() {
        $tipoUsuarioRouter = new TipoUsuarioRouter($this->app);
    }

    private function usuario() {
        $usuarioRouter = new UsuarioRouter($this->app);
    }
    
    private function pessoa() {
        $pessoaRouter = new PessoaRouter($this->app);
    }

    private function veiculo() {
        $veiculoRouter = new VeiculoRouter($this->app);
    }

    private function posto() {
        $postoRouter = new PostoRouter($this->app);
    }

} 