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
use app\combustivel\CombustivelRouter as CombustivelRouter;
use app\comentario\ComentarioRouter as ComentarioRouter; 
use app\postoCombustivel\PostoCombustivelRouter as PostoCombustivelRouter; 
use app\abastecido\AbastecidoRouter as AbastecidoRouter; 

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
        $this->combustivel();
        $this->comentario();
        $this->postoCombustivel();
        $this->abastecido();
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

    private function combustivel() {
        $combustivelRouter = new CombustivelRouter($this->app);
    }
    
    private function comentario() {
        $comentarioRouter = new ComentarioRouter($this->app);
    }

    private function postoCombustivel() {
        $postoCombustivelRouter = new PostoCombustivelRouter($this->app);
    }

    private function abastecido() {
        $abastecidoRouter = new AbastecidoRouter($this->app);
    }

} 