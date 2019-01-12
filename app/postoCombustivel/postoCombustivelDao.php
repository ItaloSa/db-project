<?php

namespace app\postoCombustivel;

use\PDO as PDO;
use \Exception as Exception;

use app\postoCombustivel\PostoCombustivel as PostoCombustivel;

use app\posto\Posto as Posto;
use app\combustivel\Combustivel as Combustivel;
use app\posto\PostoDao as PostoDao;

use app\util\DataBase as DataBase;

class PostoCombustivelDao{

	public function insert(PostoCombustivel $postoCombustivel){
		$sql = "
			INSERT INTO posto_combustivel (
				posto_cnpj, 
				combustivel_nome
			) VALUES (
				:posto_cnpj, 
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
			SELECT * FROM posto_combustivel pc
			JOIN combustivel c
				ON pc.combustivel_nome = c.nome
			JOIN posto_completo poc
				ON pc.posto_cnpj = poc.cnpj
			WHERE pc.combustivel_nome = :combustivel_nome
			AND pc.posto_cnpj = :posto_cnpj
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':combustivel_nome', $postoCombustivel->getCombustivel()->getNome());                
        $stmt->bindValue(':posto_cnpj', $postoCombustivel->getPosto()->getCnpj());     
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populatePostoCombustivel($result);
	}


	public function getAll(){
		$sql = "
			SELECT * FROM posto_combustivel pc
			JOIN combustivel c
				ON pc.combustivel_nome = c.nome
			JOIN posto_completo poc
				ON pc.posto_cnpj = poc.cnpj
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

	public function update($combustivelNome, $postoCnpj, PostoCombustivel $postoCombustivel) {
        $sql = "
            UPDATE posto_combustivel SET 
				combustivel_nome = :combustivel_nome,
				posto_cnpj = :posto_cnpj
			WHERE combustivel_nome = :combustivel_nome_old
			AND posto_cnpj = :posto_cnpj_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $postoCombustivel);
        $stmt->bindValue(':combustivel_nome_old', $combustivelNome);                
        $stmt->bindValue(':posto_cnpj_old', $postoCnpj);                
        $stmt->execute();
        $posto_combustivel = $this->get($postoCombustivel);
        return $postoCombustivel;
    }

    public function delete($combustivelNome, $postoCnpj){
		$sql = "
			DELETE FROM posto_combustivel
			WHERE combustivel_nome = :combustivel_nome
			AND posto_cnpj = :posto_cnpj
		";

		$dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':combustivel_nome', $combustivelNome);
        $stmt->bindValue(':posto_cnpj', $postoCnpj);
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
	}

	public function populatePostoCombustivel($data): PostoCombustivel {
		$postoCombustivel = new PostoCombustivel();
	
		$postoDao = new PostoDao();
		$posto = $postoDao->populatePosto($data);
		$postoCombustivel->setPosto($posto);

		$combustivel = new Combustivel();
		$combustivel->setNome($data['nome']);			
		$postoCombustivel->setCombustivel($combustivel);
		
		return $postoCombustivel;
	}

	private function bindValues($stmt, PostoCombustivel $postoCombustivel) {
		$stmt->bindValue(':posto_cnpj', $postoCombustivel->getPosto()->getCnpj());
		$stmt->bindValue(':combustivel_nome', $postoCombustivel->getCombustivel()->getNome());
		
		return $stmt;
	}
	
}