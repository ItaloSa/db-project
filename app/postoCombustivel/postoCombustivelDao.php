<?php

namespace app\postoCombustivel;

use\PDO as PDO;
use \Exception as Exception;

use app\postoCombustivel\PostoCombustivel as PostoCombustivel;

use app\posto\Posto as Posto;
use app\combustivel\Combustivel as Combustivel;

use app\util\DataBase as DataBase;

class PostoCombustivelDao{

	public function insert(PostoCombustivel $posto_combustivel){
		$sql = "
			INSERT INTO postoCombustivel (
				posto_combustivel, 
				combustivel_nome
			) VALUES (
				:posto_combustivel, 
				:combustivel_nome
			)
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt = $this->bindValues($stmt, $postoCombustivel);
		$stmt->execute();
		if ($stmt->rowCount() < 1) {
			throw new Exception("Can't create");
		}	
	}

	public function get(PostoCombustivel $postoCombustivel){
		$sql = "
			SELECT * FROM postoCombustivel
			WHERE posto_combustivel = :posto_combustivel
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':posto_combustivel', $postoCombustivel->getPosto());
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populatePostoCombustivel($result);
	}


	public function getAll(){
		$sql = "
			SELECT * FROM postoCombustivel
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->execute();

		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
			
		}

		$postosCombustiveis = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($result as $data) {
            $postosCombustiveis[] = $this->populatePostoCombustivel($data);
        }

		return $postosCombustiveis;
	}

	public function update($posto_combustivel, PostoCombustivel $postoCombustivel) {
        $sql = "
            UPDATE postoCombustivel SET 
				posto_combustivel = :posto_combustivel,
				combustivel_nome = :combustivel_nome
            WHERE posto_combustivel = :old_posto
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $postoCombustivel);
        $stmt->bindValue(':old_posto', $posto_combustivel);                
        $stmt->execute();
        $posto_combustivel = $this->get($postoCombustivel);
        return $postoCombustivel;
    }

    public function delete(PostoCombustivel $postoCombustivel){
		$sql = "
			DELETE FROM postoCombustivel
			WHERE posto_combustivel = :posto_combustivel
		";

		$dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':posto', $postoCombustivel->getPosto());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
	}

	private function populatePostoCombustivel($data): PostoCombustivel {
		$postoCombustivel = new PostoCombustivel();
		$postoCombustivel->setCombustivel($data['combustivel_nome']);
	
		if (isset($data['posto_combustivel'])) {
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
			$postoCombustivel->setPosto($posto);
		}
		if (isset($data['combustivel_nome'])) {
			$combustivel = new Combustivel();
			$combustivel->setNome($data['nome']);			
			$postoCombustivel->setCombustivel($combustivel);
		}
		return $postoCombustivel;
	}

	private function bindValues($stmt, PostoCombustivel $postoCombustivel) {
		$stmt->bindValue(':posto_combustivel', $postoCombustivel->getPosto());
		$stmt->bindValue(':combustivel_nome', $postoCombustivel->getCombustivel());
		
		return $stmt;
	}





}