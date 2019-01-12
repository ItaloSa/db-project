<?php

namespace app\endereco;

use \PDO as PDO;
use \Exception as Exception;

use app\endereco\Cidade as Cidade;
use app\endereco\Bairro as Bairro;
use app\util\DataBase as DataBase;

class EnderecoDao {

    // Cidade
    public function insertCidade(Cidade $cidade) {
        $sql = "
            INSERT INTO cidade (
                nome,
                estado,
                latitude,
                longitude
            ) VALUES (
                :nome,
                :estado,
                :latitude,
                :longitude
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $cidade->getNome());
        $stmt->bindValue(':estado', $cidade->getEstado());
        $stmt->bindValue(':latitude', $cidade->getLatitude());
        $stmt->bindValue(':longitude', $cidade->getLongitude());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }

    }

    public function getCidade(Cidade $cidade) {
        $sql = "
            SELECT * FROM cidade
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $cidade->getNome());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchObject('app\endereco\Cidade');
    }

    public function getAllCidades() {
        $sql = "
            SELECT * FROM cidade
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\endereco\Cidade');
    }

    public function updateCidade($nome, $estado, Cidade $cidade) {
        $sql = "
            UPDATE cidade SET 
                nome = :nome,
                estado = :estado,
                longitude = :longitude,
                latitude = :latitude
            WHERE nome = :nome_old
            AND estado = :estado_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $cidade->getNome());
        $stmt->bindValue(':estado', $cidade->getEstado());
        $stmt->bindValue(':longitude', $cidade->getLongitude());
        $stmt->bindValue(':latitude', $cidade->getLatitude());
        $stmt->bindValue(':nome_old', $nome);
        $stmt->bindValue(':estado_old', $estado);
        
        $stmt->execute();
        $cidade = $this->getCidade($cidade);
        return $cidade;
    }

    public function deleteCidade(Cidade $cidade) {
        $sql = "
            DELETE FROM cidade
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $cidade->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    // Bairro
    public function insertBairro(Bairro $bairro) {
        $sql = "
            INSERT INTO bairro (
                nome,
                cidade_nome,
                cidade_estado
            ) VALUES (
                :nome,
                :cidade_nome,
                :cidade_estado                
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $bairro->getNome());
        $stmt->bindValue(':cidade_nome', $bairro->getCidade()->getNome());
        $stmt->bindValue(':cidade_estado', $bairro->getCidade()->getEstado());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }

    }

    public function getBairro(Bairro $bairro) {
        $sql = "
            SELECT 
                b.nome as bairro_nome, 
                c.nome as cidade_nome, 
                c.estado, 
                c.latitude, 
                c.longitude 
            FROM bairro b, cidade c
            WHERE b.nome = :bairro_nome;
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':bairro_nome', $bairro->getNome());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $this->populateBairro($stmt->fetch(PDO::FETCH_ASSOC));

    }

    public function getAllBairros() {
        $sql = "
            SELECT 
                b.nome as bairro_nome, 
                c.nome as cidade_nome, 
                c.estado, 
                c.latitude, 
                c.longitude 
            FROM bairro b
            JOIN cidade c
                ON b.cidade_nome = c.nome
                AND b.cidade_estado = c.estado
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }

        $bairros = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $data) {
            $bairros[] = $this->populateBairro($data);
        }

        return $bairros;
    }

    public function updateBairro($nome, Bairro $bairro) {
        $sql = "
            UPDATE bairro SET 
                nome = :nome,
                cidade_nome = :cidade_nome,
                cidade_estado = :cidade_estado
            WHERE nome = :nome_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $bairro->getNome());
        $stmt->bindValue(':cidade_nome', $bairro->getCidade()->getNome());
        $stmt->bindValue(':cidade_estado', $bairro->getCidade()->getEstado());
        $stmt->bindValue(':nome_old', $nome);
        
        $stmt->execute();
        $cidade = $this->getBairro($bairro);
        return $cidade;
    }

    public function deleteBairro(Bairro $bairro) {
        $sql = "
            DELETE FROM bairro
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $bairro->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    // UTIL
    private function populateBairro($data): Bairro {
        $bairro = new Bairro();
        $bairro = new Bairro();
        $bairro->setNome($data["bairro_nome"]);
        $bairro->setCidade($this->populateCidade($data));
        return $bairro;
    }

    private function populateCidade($data): Cidade {
        $cidade = new Cidade();
        $cidade->setNome($data["cidade_nome"]);
        $cidade->setEstado($data["estado"]);
        $cidade->setLatitude($data["latitude"]);
        $cidade->setLongitude($data["longitude"]);
        return $cidade;
    }

}