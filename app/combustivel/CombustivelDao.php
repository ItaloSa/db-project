<?php

namespace app\combustivel;

use \PDO as PDO;
use \Exception as Exception;

use app\combustivel\Combustivel as Combustivel;
use app\util\DataBase as DataBase;

class CombustivelDao {

	public function insert(Combustivel $combustivel) {
        $sql = "
            INSERT INTO combustivel (nome)
            VALUES (:nome)
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $combustivel->getNome());
       
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
    }

    public function get(Combustivel $combustivel) {
        $sql = "
            SELECT * FROM tipo_usuario
            WHERE nome = :nome
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $combustivel->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchObject('app\combustivel\Combustivel');
    }


    public function getAll() {	

    	$sql = "
            SELECT * FROM combustivel
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {

            throw new Exception("Not found", 404);
        }
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\combustivel\Combustivel');

    }

    public function delete(Combustivel $combustivel) {
        $sql = "
            DELETE FROM combustivel
            WHERE nome = :nome
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $combustivel->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }




}
