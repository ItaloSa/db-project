<?php

namespace app\endereco;

use \PDO as PDO;
use \Exception as Exception;

use app\endereco\Cidade as Cidade;
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

}