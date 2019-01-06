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

}