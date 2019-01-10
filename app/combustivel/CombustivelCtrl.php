<?php

namespace app\combustivel;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\combustivel\Combustivel as Combustivel;
use app\combustivel\CombustivelDao as CombustivelDao;


class TipoUsuarioCtrl {

	public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $combustivel = new Cambustivel();
            $combustivel->setNome($data['nome']);
            $CombustivelDao = new CombustivelDao();
            $CombustivelDao->insert($combustivel);
            return $combustivel;
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
            $combustivelDao = new CombustivelDao();
            $result = $combustivelDao->getAll();
            if (sizeof($result) > 0) {
                $combustivelLista = [];
                foreach($result as $combustivel) {
                    $combustivelLista[] = $combustivel->json();
                }
                return $combustivelLista;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }


    public function delete($nome) {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $combustivel = new Combustivel();
            $combustivel->setNome($nome);
            $combustivelDao = new CombustivelDao();
            return $combustivelDao->delete($combustivel);
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