<?php

namespace app\preco;

use\PDO as PDO;
use \Exception as Exception;

use app\posto\Preco as Preco;

use app\combustivel\Combustivel as Combustivel;
use app\posto\Posto as Posto;

use app\util\DataBase as DataBase;

class PrecoDao{

	public function insert(Preco $preco){
		$sql = "
			INSERT INTO preco (
				momento,
				valor,
				combustivel,
				posto
			) VALUES (
				:momento,
				:valor,
				:combustivel,
				:posto				
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
				combustivel = :combustivel,
				posto = :posto
				
            WHERE momento = :old_momento
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $posto);
        $stmt->bindValue(':old_momento', $momento);                
        $stmt->execute();
        $posto = $this->get($posto);
        return $posto;
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
		if (isset($data['posto'])) {
			$posto = new Posto();
			$posto->setCnpj($data['cnpj']);
			$posto->setRazaoSocial($data['razaoSocial']);
			$posto->setNomeFantasia($data['nomeFantasia']);
			$posto->setLatitude($data['latitude']);
			$posto->setLongitude($data['longitude']);
			$posto->setEndereco($data['endereco']);
			$posto->setTelefone($data['telefone']);
			$posto->setBandeira($data['bandeira']);
			$posto->setBairro($data['bairro']);
			$preco->setPosto($posto);
		}
		if (isset($data['combustivel'])) {
			$combustivel = new Combustivel();
			$combustivel->setNome($data['nome']);			
			$preco->setCombustivel($combustivel);
		}
		return $preco;
	}

	private function bindValues($stmt, Preco $preco) {
		$stmt->bindValue(':momento', $preco->getMomento());
		$stmt->bindValue(':valor', $preco->getValor());
		$stmt->bindValue(':combustivel', $preco->getCombustivel());
		$stmt->bindValue(':posto', $preco->getPosto());
		
		return $stmt;
	}





}