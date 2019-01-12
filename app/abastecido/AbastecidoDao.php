<?php

namespace app\abastecido;

use\PDO as PDO;
use \Exception as Exception;

use app\abastecido\Abastecido as Abastecido;
use app\combustivel\Combustivel as Combustivel;
use app\veiculo\Veiculo as Veiculo;
use app\veiculo\VeiculoDao as VeiculoDao;


use app\util\DataBase as DataBase;

class AbastecidoDao{

	public function insert(Abastecido $abastecido){
		$sql = "
			INSERT INTO abastecido (
				combustivel_nome, 
				veiculo_placa 
				
			) VALUES (
				:combustivel_nome, 
				:veiculo_placa
				
			)
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt = $this->bindValues($stmt, $abastecido);
		$stmt->execute();
		if ($stmt->rowCount() < 1) {
			throw new Exception("Can't create");
		}	
	}

	public function get(Abastecido $abastecido){
		$sql = "
			SELECT * FROM abastecido a
			JOIN veiculo v
				ON a.veiculo_placa = v.placa
			JOIN pessoa_completa pc
				ON v.pessoa_login = pc.login
			JOIN combustivel c
				ON a.combustivel_nome = c.nome
			WHERE a.combustivel_nome = :combustivel_nome
			AND a.veiculo_placa = :veiculo_placa
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':combustivel_nome', $abastecido->getCombustivel()->getNome());
		$stmt->bindValue(':veiculo_placa', $abastecido->getVeiculo()->getPlaca());
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populateAbastecido($result);
	}

	public function getAll(){
		$sql = "
			SELECT * FROM abastecido a
			JOIN veiculo v
				ON a.veiculo_placa = v.placa
			JOIN pessoa_completa pc
				ON v.pessoa_login = pc.login
			JOIN combustivel c
				ON a.combustivel_nome = c.nome
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->execute();

		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
			
		}

		$abastecidos = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($result as $data) {
            $abastecidos[] = $this->populateAbastecido($data);
        }

		return $abastecidos;
	}

	public function update($combustivel_nome, $veiculo_placa, Abastecido $abastecido) {
        $sql = "
            UPDATE abastecido SET 
				combustivel_nome = :combustivel_nome,
				veiculo_placa = :veiculo_placa
				
            WHERE combustivel_nome = :combustivel_nome_old
            AND veiculo_placa = :veiculo_placa_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $abastecido);
        $stmt->bindValue(':combustivel_nome_old', $combustivel_nome);   
        $stmt->bindValue(':veiculo_placa_old', $veiculo_placa);             
        $stmt->execute();
        $abastecido= $this->get($abastecido);
        return $abastecido;
    }

    public function delete($combustivel_nome, $veiculo_placa){

		$sql = "
			DELETE FROM abastecido
			WHERE combustivel_nome = :combustivel_nome
			AND veiculo_placa = :veiculo_placa
		";

		$dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
       $stmt->bindValue(':combustivel_nome', $combustivelNome);
        $stmt->bindValue(':veiculo_placa', $veiculo_placa);

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
	}

	public function populateAbastecido($data): Abastecido {
		$abastecido= new Abastecido();

		$veiculoDao = new VeiculoDao();
		$veiculo = $veiculoDao->populateVeiculo($data);
		$abastecido->setVeiculo($veiculo);

		$combustivel = new Combustivel();
		$combustivel->setNome($data['nome']);			
		$abastecido->setCombustivel($combustivel);

		return $abastecido;
		
	}

	private function bindValues($stmt, Abastecido $abastecido) {
		$stmt->bindValue(':combustivel_nome', $abastecido->getCombustivel()->getNome());
		$stmt->bindValue(':veiculo_placa', $abastecido->getVeiculo()->getPlaca());
		
		return $stmt;
	}
	
}