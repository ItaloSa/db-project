<?php

namespace app\posto;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\posto\Posto as Posto;
use app\posto\PostoDao as PostoDao;

class PostoCtrl {

	public function create($data): Bandeira {

		if($data == null){
			throw new Exception("Data can't be empty");				

		}

		try{
			$posto = new Posto();
			$posto->setCnpj($data["cnpj"]);
			$posto->setRazaoSocial($data["razaoSocial"]);
			$posto->setNomeFantasia($data["nomeFantasia"]);
			$posto->setLatitude($data["latitude"]);
			$posto->setLongitude($data["longitude"]);
			$posto->setEndereco($data["endereco"]);
			$posto->setTelefone($data["telefone"]);
			$posto->setBandeira($data["bandeira"]);
			$postoDao = new PostoDao();
			$postoDao->insert($posto);
			return $posto;
		}catch (Error $e) {
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
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function delete($nomeFantasia) {
        if ($nomeFantasia == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $posto = new Posto();
            $posto->setNomeFantasia($nomeFantasia);
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


}