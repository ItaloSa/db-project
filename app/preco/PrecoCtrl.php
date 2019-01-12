<?php

namespace app\preco;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\preco\Preco as Preco;
use app\posto\PostoCtrl as PostoCtrl;
use app\combustivel\CombustivelCtrl as CombustivelCtrl;

class PrecoCtrl{

	public function create($data): Preco {
		if($data == null){
			throw new Exception("Data can't be empty");				
        }
        
		try{
			$preco = $this->mountPreco($data);
			$precoDao = new PrecoDao();
			$precoDao->insert($preco);
			return $preco;
		}catch (Error $e) {
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

	public function get($momento): Preco {
        if ($momento == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $preco = new Preco();
            $preco->setMomento($momento);
            $precoDao = new PrecoDao();
            return $precoDao->get($preco);
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

    public function getAll(): array {
        try {
            $precoDao = new PrecoDao();
            $result = $precoDao->getAll();
            if (sizeof($result) > 0) {
                $precos = [];
                foreach($result as $preco) {
                    $precos[] = $preco->json();
                }
                return $precos;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function update($momento, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $preco = $this->mountPreco($data);
            $postoDao = new PrecoDao();
            $preco = $precoDao->update($momento, $preco);
            return $preco;
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

    public function delete($momento) {
        if ($momento == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $preco = new Preco();
            $preco->setMomento($momento);
            $precoDao = new PrecoDao();
            return $precoDao->delete($preco);
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

    public function mountPreco($data): Preco {
        $preco = new Preco;
		$preco->setMomento($data['momento']);
		$preco->setValor($data['valor']);
		if (isset($data['posto'])) {
			$posto = new Posto();
			$posto->setCnpj($data['cnpj']);
			$posto->setRazaoSocial($data['razaoSocial']);
			$posto->setNomeFantasia($data['nomeFantasia']);
			$posto->setLatitude($data['latitude']);
			$posto->setLongitude($data['longitude']);
			$posto->setEndereco($data['endereco']);
			$posto->setTelefone($data['telefone']);
			$posto->setBandeira($data['bandeira']);
			$posto->setBairro($data['bairro']);
			$preco->setPosto($posto);
		}
		if (isset($data['combustivel'])) {
			$combustivel = new Combustivel();
			$combustivel->setNome($data['nome']);			
			$preco->setCombustivel($combustivel);
		}
		return $preco;
    }






}
