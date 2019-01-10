<?php

namespace app\veiculo;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\veiculo\Veiculo as Veiculo;
use app\veiculoo\VeiculoDao as VeiculoDao;

class VeiculoCtrl {

    public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $veiculo = $this->mountVeiculo($data);
            $veiculoDao = new VeiculoDao();
            $veiculoDao->insert($veiculo);
            return $veiculo;
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
            $veiculoDao = new VeiculoDao();
            $result = $veiculoDao->getAll();
            if (sizeof($result) > 0) {
                $veiculos = [];
                foreach($result as $veiculo) {
                    $veiculos[] = $veiculo->json();
                }
                return $veiculos;
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

    public function update($placa, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $veiculo = $this->mountVeiculo($data);
            $veiculo->setPlaca($placa);
            $veiculoDao = new VeiculoDao();
            $veiculo = $veiculoDao->update($veiculo);
            return $veiculo;
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


    public function delete($placa) {
        if ($placa == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $veiculo = new Veiculo();
            $veiculo->setLogin($login);
            $veiculoDao = new VeiculoDao();
            return $veiculoDao->delete($veiculo);
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

    private function mountVeiculo($data): Veiculo {
        $veiculo = new Veiculo();
        $veiculo->setPlaca($data['placa']);
        $veiculo->setMarca($data['marca']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setPessoaLogin($data['pessoaLogin']);
        return $veiculo;
    }






}    