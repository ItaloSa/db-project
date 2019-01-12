<?php

namespace app\abastecido;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\abastecido\Abastecido as Abastecido;
use app\abastecido\AbastecidoDao as AbastecidoDao;
use app\veiculo\VeiculoDao as VeiculoDao;
use app\combustivel\CombustivelCtrl as CombustivelCtrl;
use app\combustivel\Combustivel as Combustivel;
use app\veiculo\VeiculoCtrl as VeiculoCtrl;


class AbastecidoCtrl{

	public function create($data): Abastecido {
		if($data == null){
			throw new Exception("Data can't be empty");				
        }
        
		try{
			$abastecido = $this->mountAbastecido($data);
			$abastecidoDao = new AbastecidoDao();
			$abastecidoDao->insert($abastecido);
			return $abastecido;
		}catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            var_dump($e->errorInfo);die();
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

	public function get($combustivel_nome, $veiculo_placa): Abastecido {
        if ($combustivel_nome == null) {
            throw new Exception("Data can't be empty");
        }

        if ($veiculo_placa == null) {
            throw new Exception("Data can't be empty");
        }


        try {
            $abastecido = new Abastecido();
            $abastecido->setCombustivel($combustivel_nome);
            $abastecido->setVeiculo($veiculo_placa);
            $abastecidoDao = new AbastecidoDao();
            return $abastecidoDao->get($abastecido);
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
            $abastecidoDao = new AbastecidoDao();
            $result = $abastecidoDao->getAll();
            if (sizeof($result) > 0) {
                $abastecidos = [];
                foreach($result as $abastecido) {
                    $abastecidos[] = $abastecido->json();
                }
                return $abastecidos;
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

    public function update($combustivel_nome, $veiculo_placa, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $abastecido = $this->mountAbastecido($data);
            $abastecidoDao = new AbastecidoDao();
            $abastecido = $abastecidoDao->update($combustivel_nome, $veiculo_placa, $abastecido);
            return $abastecido;
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

    public function delete($combustivel_nome, $veiculo_placa) {
        if ($combustivel_nome == null) {
            throw new Exception("Data can't be empty");
        }

        if ($veiculo_placa == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $abastecidoDao = new AbastecidoDao();
            return $abastecidoDao->delete($combustivel_nome, $veiculo_placa);
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

    private function mountAbastecido($data): Abastecido {
        $abastecido = new Abastecido();
        $veiculoDao = new VeiculoCtrl();
        $veiculo = $veiculoDao->mountVeiculo($data['veiculo']);
		$abastecido->setVeiculo($veiculo);

		$combustivel = new Combustivel();
		$combustivel->setNome($data['combustivel']['nome']);			
		$abastecido->setCombustivel($combustivel);
        return $abastecido;
    }



}
