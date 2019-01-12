<?php

namespace app\posto;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\posto\Posto as Posto;
use app\posto\PostoDao as PostoDao;
use app\bandeira\BandeiraCtrl as BandeiraCtrl;
use app\endereco\EnderecoCtrl as EnderecoCtrl;

class PostoCtrl {

	public function create($data): Posto {
		if($data == null){
			throw new Exception("Data can't be empty");				
        }
        
		try{
			$posto = $this->mountPosto($data);
			$postoDao = new PostoDao();
			$postoDao->insert($posto);
			return $posto;
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

	public function get($nomeFantasia): Posto {
        if ($nomeFantasia == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $posto = new Posto();
            $posto->setNomeFantasia($nomeFantasia);
            $postoDao = new PostoDao();
            return $postoDao->get($posto);
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
            $postoDao = new PostoDao();
            $result = $postoDao->getAll();
            if (sizeof($result) > 0) {
                $postos = [];
                foreach($result as $posto) {
                    $postos[] = $posto->json();
                }
                return $postos;
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

    public function update($cnpj, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $posto = $this->mountPosto($data);
            $postoDao = new PostoDao();
            $posto = $postoDao->update($cnpj, $posto);
            return $posto;
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

    public function delete($cnpj) {
        if ($cnpj == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $posto = new Posto();
            $posto->setCnpj($cnpj);
            $postoDao = new PostoDao();
            return $postoDao->delete($posto);
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

    public function mountPosto($data): Posto {
        $posto = new Posto();
        $posto->setCnpj($data["cnpj"]);
        $posto->setRazaoSocial($data["razaoSocial"]);
        if (isset($data["nomeFantasia"])) {
            $posto->setNomeFantasia($data["nomeFantasia"]);
        }
        $posto->setLatitude($data["latitude"]);
        $posto->setLongitude($data["longitude"]);
        $posto->setEndereco($data["endereco"]);
        if (isset($data["telefone"])) {
            $posto->setTelefone($data["telefone"]);
        }
        if (isset($data['bandeira'])) {
            $bandeiraCtrl = new BandeiraCtrl();
            $bandeira = $bandeiraCtrl->mountBandeira($data['bandeira']);
            $posto->setBandeira($bandeira);
        }
        if (isset($data['bairro'])) {
            $enderecoCtrl = new EnderecoCtrl();
            $bairro = $enderecoCtrl->mountBairro($data['bairro']);
            $posto->setBairro($bairro);
        }
        return $posto;
    }

}