<?php

namespace app\preco;

use\PDO as PDO;
use \Exception as Exception;



use app\precoCombustivel\PrecoCombustivel as PrecoCombustivel;


use app\util\DataBase as DataBase;

class PrecoDao{

	public function insert(Preco $preco){
		$sql = "
			INSERT INTO preco (
				momento,
				valor,
				combustivel_nome,
				posto_cnpj
			) VALUES (
				:momento,
				:valor,
				:combustivel_nome,
				:posto_cnpj				
			)
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt = $this->bindValues($stmt, $preco);
		$stmt->execute();
		if ($stmt->rowCount() < 1) {
			throw new Exception("Can't create");
		}	
	}

	public function get(Preco $preco){
		$sql = "
			SELECT * FROM preco
			WHERE momento = :momento
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':momento', $preco->getMomento());
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populatePreco($result);
		
	}

	public function getAll() {
        $sql = "
            SELECT * FROM preco
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }

        $precos = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($result as $data) {
            $precos[] = $this->populatePreco($data);
        }

		return $precos;
    }

    public function update($momento, Preco $preco) {
        $sql = "
            UPDATE preco SET 
				momento = :momento,
				valor = :valor,
				combustivel_nome = :combustivel_nome,
				posto_cnpj = :posto_cnpj
				
            WHERE momento = :old_momento
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $preco);
        $stmt->bindValue(':old_momento', $momento);                
        $stmt->execute();
        $preco = $this->get($preco);
        return $preco;
    }

    public function delete(Preco $preco) {
        $sql = "
            DELETE FROM preco
            WHERE momento = :momento
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':momento', $preco->getMomento());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    private function populatePreco($data): Preco {
		$preco = new Preco;
		$preco->setMomento($data['momento']);
		$preco->setValor($data['valor']);
		if (isset($data['combustivel_nome'])) {
			$postoCombustivelDao = new postoCombustivelDao();
			$posto = $postoCombustivelDao->populatePostoCombustivel($data);
			$preco->setPostoCombustivel($)

			$postoCombustivel->setPosto($data['posto']);
			$postoCombustivel->setCombustivel($data['combustivel']);
			
			$preco->setPostoCombustivel($postoCombustivel);
		}
		
		return $preco;
	}

	private function bindValues($stmt, Preco $preco) {
		$stmt->bindValue(':momento', $preco->getMomento());
		$stmt->bindValue(':valor', $preco->getValor());
		$stmt->bindValue(':combustivel_nome', $preco->getPostoCombustivel()->getCombustivel()->getNome());
		$stmt->bindValue(':posto_cnpj', $preco->getPostoCombustivel()->getPosto()->getCnpj());
		
		return $stmt;
	}





}