<?php

namespace app\posto;

use\PDO as PDO;
use \Exception as Exception;

use app\posto\Posto as Posto;
use app\util\DataBase as DataBase;

	class PostoDao {

		public function insert(Posto $posto){

		$sql = "

			INSERT INTO posto (cnpj, razao_social, nome_fantasia, latitude, longitude
			endereco, telefone, bandeira_nome, bairro_nome)
			VALUES (:cnpj, :razao_social, :nome_fantasia, :latitude, :longitude,
			:endereco, :telefone, :bandeira_nome, :bairro_nome)
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
		$stmt->bindValue(':bandeira_nome', $posto->getBandeira());

		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Can´t create");
		}

	}

	public function get(Posto $posto){
		$sql = "
			SELECT * FROM posto
			WHERE nomeFantasia = :nomeFantasia

		";

		$dataBase = DataBase::getInstance();
		$stmt = $dataBase->prepare($sql);
		$stmt->bindValue(':nome', $posto->getNomeFantasia());
		$stmt->execute();
		if ($stmt->rowCount() < 1){
			throw new Exception("Not found", 404);

		}

		return $stmt->fetchObject('app\posto\Posto');
	
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
		return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\posto\Posto');


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










}