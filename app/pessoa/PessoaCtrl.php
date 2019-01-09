<?php

namespace app\pessoa;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;
use app\pessoa\Pessoa as Pessoa;

use app\Pessoa\PessoaDao as PessoaDao;

class PessoaCtlr {

	public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $usuario = $this->mountUsuario($data);
            $usuarioDao = new UsuarioDao();
            $usuarioDao->insert($usuario);
            return $usuario;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == "23000") {
                throw new Exception("Duplicate entry");
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function getAll() {
        try {
            $pessoaDao = new PessoaDao();
            $result = $PessoaDao->getAll();
            if (sizeof($result) > 0) {
                $pessoas = [];
                foreach($result as $pessoa) {
                    $pessoas[] = $pessoa->json();
                }
                return $pessoas;
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

    public function update($login, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $pessoa = $this->mountPessoa($data);
            $pessoa->setLogin($login);
            $pessoaDao = new PessoaDao();
            $pessoa = $pessoaDao->update($pessoa);
            return $pessoa;
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

    public function delete($login) {
        if ($login == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $pessoa = new Pessoa();
            $pessoa->setLogin($login);
            $pessoaDao = new PessoaDao();
            return $pessoaDao->delete($pessoa);
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

    private function mountPessoa($data): Pessoa {
        $pessoa = new Usuario();
        $pessoa->setLogin($data['login']);
        $pessoa->setNome($data['nome']);
        $pessoa->setEndereco($data['endereco']);
        $pessoa->setUsuarioLogin($data['usuarioLogin']);
        $pessoa->setNome($data['nome']);
        $pessoa->setBairroNome($data['bairroNome']);
        
        return $usuario;
    }
}























}
