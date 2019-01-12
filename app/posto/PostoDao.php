<?php

namespace app\posto;

use\PDO as PDO;
use \Exception as Exception;

use app\posto\Posto as Posto;
use app\bandeira\Bandeira as Bandeira;
use app\pessoa\PessoaDao as PessoaDao;

use app\util\DataBase as DataBase;

class PostoDao {

	public function insert(Posto $posto){
		$sql = "
			INSERT INTO posto (
				cnpj, 
				razao_social, 
				nome_fantasia, 
				latitude, 
				longitude,
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
		$stmt = $this->bindValues($stmt, $posto);
		$stmt->execute();
		if ($stmt->rowCount() < 1) {
			throw new Exception("Can't create");
		}	
	}

	public function get(Posto $posto){
		$sql = "
			SELECT * FROM posto_completo
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
		return $this->populatePosto($result);
	}

	public function getAll(){
		$sql = "
			SELECT * FROM posto_completo
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

	public function update($cnpj, Posto $posto) {
        $sql = "
            UPDATE posto SET 
				cnpj = :cnpj ,
				razao_social = :razao_social,
				nome_fantasia = :nome_fantasia,
				latitude = :latitude, 
				longitude = :longitude,
				endereco = :endereco, 
				telefone = :telefone, 
				bandeira_nome = :bandeira_nome, 
				bairro_nome = :bairro_nome
            WHERE cnpj = :old_cnpj
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $posto);
        $stmt->bindValue(':old_cnpj', $cnpj);                
        $stmt->execute();
        $posto = $this->get($posto);
        return $posto;
    }

	public function delete(Posto $posto){
		$sql = "
			DELETE FROM posto
			WHERE cnpj = :cnpj
		";

		$dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':cnpj', $posto->getCnpj());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
	}

	public function populatePosto($data): Posto {
		$posto = new Posto();
		$posto->setCnpj($data['cnpj']);
		$posto->setRazaoSocial($data['razao_social']);
		if (isset($data['nome_fantasia'])) {
			$posto->setNomeFantasia($data['nome_fantasia']);
		}
		$posto->setLatitude($data['latitude']);
		$posto->setLongitude($data['longitude']);
		$posto->setEndereco($data['endereco']);
		if (isset($data['telefone'])) {
			$posto->setTelefone($data['telefone']);
		}
		if (isset($data['bandeira_nome'])) {
			$bandeira = new Bandeira();
			$bandeira->setNome($data['bandeira_nome']);
			$bandeira->setUrl($data['bandeira_url']);
			$posto->setBandeira($bandeira);
		}
		if (isset($data['bairro_nome'])) {
			$pessoaDao = new PessoaDao();
			$bairro = $pessoaDao->populateBairro($data);
			$posto->setBairro($bairro);
		}
		return $posto;
	}

	private function bindValues($stmt, Posto $posto) {
		$stmt->bindValue(':cnpj', $posto->getCnpj());
		$stmt->bindValue(':razao_social', $posto->getRazaoSocial());
		if ($posto->getNomeFantasia() != null) {
			$stmt->bindValue(':nome_fantasia', $posto->getNomeFantasia());
		} else {
			$stmt->bindValue(':nome_fantasia', null);
		}
		$stmt->bindValue(':latitude', $posto->getLatitude());
		$stmt->bindValue(':longitude', $posto->getLongitude());
		$stmt->bindValue(':endereco', $posto->getEndereco());
		if ($posto->getTelefone() != null) {
			$stmt->bindValue(':telefone', $posto->getTelefone());
		} else {
			$stmt->bindValue(':telefone', null);
		}
		if ($posto->getBandeira() != null) {
			$stmt->bindValue(':bandeira_nome', $posto->getBandeira()->getNome());
		} else {
			$stmt->bindValue(':bandeira_nome', null);			
		} 
		if($posto->getBairro() != numfmt_get_locale) {
			$stmt->bindValue(':bairro_nome', $posto->getBairro()->getNome());
		} else {
			$stmt->bindValue(':bairro_nome', null);
		}
		return $stmt;
	}

}
