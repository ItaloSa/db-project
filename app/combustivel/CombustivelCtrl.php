<?php

namespace app\combustivel;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\combustivel\Combustivel as Combustivel;
use app\combustivel\CombustivelDao as CombustivelDao;


class CombustivelCtrl {

	public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $combustivel = new Combustivel();
            $combustivel->setNome($data['nome']);
            $CombustivelDao = new CombustivelDao();
            $CombustivelDao->insert($combustivel);
            return $combustivel;
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
            $combustivelDao = new CombustivelDao();
            $result = $combustivelDao->getAll();
            if (sizeof($result) > 0) {
                $combustiveis = [];
                foreach($result as $combustivel) {
                    $combustiveis[] = $combustivel->json();
                }
                return $combustiveis;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function update($nome, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $combustivel = new Combustivel();
            $combustivel->setNome($data['nome']);
            $combustivelDao = new CombustivelDao();
            $combustivel = $combustivelDao->update($nome, $combustivel);
            return $combustivel;
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