<?php

namespace app\posto;

use\PDO as PDO;
use \Exception as Exception;

use app\posto\Posto as Posto;
use app\bandeira\Bandeira as Bandeira;

use app\util\DataBase as DataBase;

	class PostoDao {

		public function insert(Posto $posto){

		$sql = "
			INSERT INTO posto (
				cnpj, 
				razao_social, 
				nome_fantasia, 
				latitude, 
				longitude
				endereco, 
				telefone, 
				bandeira_nome, 
				bairro_nome
			) VALUES (
				:cnpj, 
				:razao_social, 
				:nome_fantasia, 
				:latitude, 
				:longitude,
				:endereco, 
				:telefone, 
				:bandeira_nome,
				:bairro_nome
			)
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);

		$stmt->bindValue(':cnpj', $posto->getCnpj());
		$stmt->bindValue(':razao_social', $posto->getRazaoSocial());
		$stmt->bindValue(':nome_fantasia', $posto->getNomeFantasia());
		$stmt->bindValue(':latitude', $posto->getLatitude());
		$stmt->bindValue(':longitudee', $posto->getLongitude());
		$stmt->bindValue(':endereco', $posto->getEndereco());
		$stmt->bindValue(':telefone', $posto->getTelefone());
		$stmt->bindValue(':bandeira_nome', $posto->getBandeira()->getNome());
		$stmt->bindValue(':bairro_nome', $posto->getBairro()->getNome());

		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Can´t create");
		}

	}

	public function get(Posto $posto){
		$sql = "
			SELECT * FROM posto
			WHERE cnpj = :cnpj

		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':cnpj', $posto->getCnpj());
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);

		}

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $this->populatePosto($data);
	}

	public function getAll(){
		$sql = "
			SELECT * FROM posto
		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->execute();

		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);
			
		}

		$postos = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $data) {
            $postos[] = $this->populatePosto($data);
        }

		return $postos;
	}

	public function delete(Posto $posto){
		$sql = "

			DELETE FROM posto
			WHERE nomeFantasia = :nomeFantasia

		";

		$dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $bandeira->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
	}

	private function populatePosto($data): Posto {
		$posto = new Posto();
		$posto->setCnpj($data['cnpj']);
		$posto->setRazaoSocial($data['razao_social']);
		$posto->setNomeFantasia($data['nome_fantasia']);
		$posto->setLatitude($data['latitude']);
		$posto->setLongitude($data['longitude']);
		$posto->setEndereco($data['endereco']);
		$posto->setTelefone($data['telefone']);
		$bandeira = new Bandeira();
		$bandeira->setNome($data['bandeira_nome']);
		$bandeira->setUrl($data['bandeira_url']);
		$posto->setBandeira($bandeira);
		
	}

}