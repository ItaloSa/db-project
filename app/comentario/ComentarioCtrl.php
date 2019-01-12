<?php

namespace app\comentario;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\comentario\Comentario as Comentario;
use app\comentario\ComentarioDao as ComentarioDao;
use app\pessoa\PessoaCtrl as PessoaCtrl;
use app\pessoa\Pessoa as Pessoa;
use app\posto\PostoCtrl as PostoCtrl;
use app\posto\Posto as Posto;

class ComentarioCtrl {

    public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $comentario = $this->mountComentario($data);
            $comentarioDao = new ComentarioDao();
            $comentarioDao->insert($comentario);
            return $comentario;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->errorInfo[1] == 1452) {
                throw new Exception("Can't Create");
            } else if ($e->errorInfo[1] == 1062) {
                throw new Exception("Duplicate entry");
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function getAll() {
        try {
            $comentarioDao = new ComentarioDao();
            $result = $comentarioDao->getAll();
            if (sizeof($result) > 0) {
                $comentarios = [];
                foreach($result as $comentario) {
                    $comentarios[] = $comentario->json();
                }
                return $comentarios;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function update($postoCnpj, $pessoaLogin, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $comentario = $this->mountComentario($data);
            $comentarioDao = new ComentarioDao();
            $comentario = $comentarioDao->update($postoCnpj, $pessoaLogin, $comentario);
            return $comentario;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function delete($postoCnpj, $pessoaLogin) {
        
        try {
            $comentario = new Comentario();
            $posto = new Posto();
            $posto->setCnpj($postoCnpj);
            $comentario->setPosto($posto);
            $pessoa = new Pessoa();
            $pessoa->setLogin($pessoaLogin);
            $comentario->setPessoa($pessoa);

            $comentarioDao = new ComentarioDao();
            return $comentarioDao->delete($comentario);
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    private function mountComentario($data): Comentario {
        $comentario = new Comentario();
        $comentario->setMomento($data['momento']);
        $pessoaCtrl = new PessoaCtrl();
        $pessoa = $pessoaCtrl->mountPessoa($data['pessoa']);
        $comentario->setPessoa($pessoa);
        $postoCtrl = new PostoCtrl();
        $posto = $postoCtrl->mountPosto($data['posto']);
        $comentario->setPosto($posto);
        return $comentario;
    }
}    
