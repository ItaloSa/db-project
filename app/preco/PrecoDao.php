<?php

namespace app\preco;

use\PDO as PDO;
use \Exception as Exception;

use app\precoCombustivel\PrecoCombustivel as PrecoCombustivel;
use app\postoCombustivel\PostoCombustivelDao as PostoCombustivelDao;

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
			SELECT * FROM preco p
			JOIN combustivel c
				ON p.combustivel_nome = c.nome
			JOIN posto_completo poc
				ON p.posto_cnpj = poc.cnpj
			WHERE momento = :momento
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':momento', $preco->getMomento()->format('Y-m-d H:i:s'));
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populatePreco($result);
		
	}

	public function getAll() {
        $sql = "
			SELECT * FROM preco p
			JOIN combustivel c
				ON p.combustivel_nome = c.nome
			JOIN posto_completo poc
				ON p.posto_cnpj = poc.cnpj
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

		$momento = str_replace("Z", "", $momento);
		$momento = new \DateTime($momento, new \DateTimeZone('UTC'));
		
        $dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $preco);
        $stmt->bindValue(':old_momento', $momento->format('Y-m-d H:i:s'));                
        $stmt->execute();
        $preco = $this->get($preco);
        return $preco;
    }

    public function delete($momento) {
        $sql = "
            DELETE FROM preco
            WHERE momento = :momento
        ";

        $dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$momento = str_replace("Z", "", $momento);
		$momento = new \DateTime($momento, new \DateTimeZone('UTC'));
        $stmt->bindValue(':momento', $momento->format('Y-m-d H:i:s'));
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    private function populatePreco($data): Preco {
		$preco = new Preco;
		$preco->setMomento($data['momento']);
		$preco->setValor($data['valor']);

		$postoCombustivelDao = new PostoCombustivelDao();
		$posto = $postoCombustivelDao->populatePostoCombustivel($data);
		$preco->setPostoCombustivel($posto);

		return $preco;
	}

	private function bindValues($stmt, Preco $preco) {
		$stmt->bindValue(':momento', $preco->getMomento()->format('Y-m-d H:i:s'));
		$stmt->bindValue(':valor', $preco->getValor());
		$stmt->bindValue(':combustivel_nome', $preco->getPostoCombustivel()->getCombustivel()->getNome());
		$stmt->bindValue(':posto_cnpj', $preco->getPostoCombustivel()->getPosto()->getCnpj());
		
		return $stmt;
	}

}