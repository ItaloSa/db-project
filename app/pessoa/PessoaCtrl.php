<?php

namespace app\pessoa;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\pessoa\Pessoa as Pessoa;
use app\pessoa\PessoaDao as PessoaDao;

use app\usuario\Usuario as Usuario;
use app\endereco\Bairro as Bairro;
use app\endereco\Cidade as Cidade;
use app\tipoUsuario\TipoUsuario as TipoUsuario;
class PessoaCtrl {

	public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $pessoa = $this->mountPessoa($data);
            $pessoaDao = new PessoaDao();
            $pessoaDao->insert($pessoa);
            return $pessoa;
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
            $pessoaDao = new PessoaDao();
            $result = $pessoaDao->getAll();
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
            $pessoaDao = new PessoaDao();
            $pessoa = $pessoaDao->update($login, $pessoa);
            return $pessoa;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else if ($e->getCode() == 400) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else  {
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

    public function mountPessoa($data): Pessoa {
        $pessoa = new Pessoa();
        $pessoa->setLogin($data['login']);
        $pessoa->setNome($data['nome']);
        $pessoa->setEndereco($data['endereco']);
        $pessoa->setBairro($this->mountBairro($data['bairro']));
        if (isset($data['usuario'])) {
            $usuario = new Usuario();
            $usuario->setLogin($data['usuario']['login']);
            $usuario->setSenha($data['usuario']['senha']);
            if (isset($data['tipoUsuario'])) {
                $tipoUsuario = new TipoUsuario();
                $tipoUsuario->setNome($data['tipoUsuario']['nome']);
                $usuario->setTipoUsuario($tipoUsuario);
            }
            $pessoa->setUsuario($usuario);
        }
        return $pessoa;
    }

    public function mountBairro($data): Bairro {
        $bairro = new Bairro();
        $bairro->setNome($data['nome']);
        $cidade = new Cidade();
        $cidade->setNome($data['cidade']['nome']);
        $cidade->setEstado($data['cidade']['estado']);
        $cidade->setLatitude($data['cidade']['latitude']);
        $cidade->setLongitude($data['cidade']['longitude']);
        $bairro->setCidade($cidade);
        return $bairro;
    }

}
