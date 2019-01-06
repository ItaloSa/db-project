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
            $cidade = new Cidade();
            $cidade->setNome($data["nome"]);
            $cidade->setEstado($data["estado"]);
            $cidade->setLatitude($data["latitude"]);
            $cidade->setLongitude($data["longitude"]);
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

}