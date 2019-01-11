<?php

namespace app\endereco;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\endereco\Cidade as Cidade;
use app\endereco\EnderecoDao as EnderecoDao;

class EnderecoCtrl {

    // Cidade
    public function createCidade($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $cidade = $this->mountCidade($data);
            $enderecoDao = new EnderecoDao();
            $enderecoDao->insertCidade($cidade);
            return $cidade;
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

    public function getCidade($nome): Cidade {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $cidade = new Cidade();
            $cidade->setNome($nome);
            $enderecoDao = new EnderecoDao();
            return $enderecoDao->getCidade($cidade);
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

    public function getAllCidades() {
        try {
            $enderecoDao = new EnderecoDao();
            $result = $enderecoDao->getAllCidades();
            if (sizeof($result) > 0) {
                $cidades = [];
                foreach($result as $cidade) {
                    $cidades[] = $cidade->json();
                }
                return $cidades;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function updateCidade($nome, $estado, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $cidade = $this->mountCidade($data);
            $cidade->setNome($nome);
            $cidade->setEstado($estado);
            $enderecoDao = new EnderecoDao();
            $cidade = $enderecoDao->updateCidade($cidade);
            return $cidade;
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

    public function deleteCidade($nome) {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $cidade = new Cidade();
            $cidade->setNome($nome);
            $enderecoDao = new EnderecoDao();
            return $enderecoDao->deleteCidade($cidade);
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

    // Bairro
    public function createBairro($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bairro = $this->mountBairro($data);  
            $enderecoDao = new EnderecoDao();
            $enderecoDao->insertBairro($bairro);
            return $bairro;
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

    public function getBairro($nome): Bairro {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bairro = new Bairro();
            $bairro->setNome($nome);
            $enderecoDao = new EnderecoDao();
            return $enderecoDao->getBairro($bairro);
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

    public function getAllBairros() {
        try {
            $enderecoDao = new EnderecoDao();
            $result = $enderecoDao->getAllBairros();
            if (sizeof($result) > 0) {
                $bairros = [];
                foreach($result as $bairro) {
                    $bairros[] = $bairro->json();
                }
                return $bairros;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function updateBairro($nome, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bairro = $this->mountBairro($data);
            $bairro->setNome($nome);
            $enderecoDao = new EnderecoDao();
            $bairro = $enderecoDao->updateBairro($bairro);
            return $bairro;
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

    public function deleteBairro($nome) {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bairro = new Bairro();
            $bairro->setNome($nome);
            $enderecoDao = new EnderecoDao();
            return $enderecoDao->deleteBairro($bairro);
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

    // UTIL
    public function mountCidade($data): Cidade {
        $cidade = new Cidade();
        $cidade->setNome($data["nome"]);
        $cidade->setEstado($data["estado"]);
        $cidade->setLatitude($data["latitude"]);
        $cidade->setLongitude($data["longitude"]);
        return $cidade;
    }

    public function mountBairro($data): Bairro {
        $bairro = new Bairro();
        $bairro->setNome($data["nome"]);
        $cidadeData = $data["cidade"];
        $bairro->setCidade($this->mountCidade($cidadeData));
        return $bairro;
    }

}