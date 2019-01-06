<?php

namespace app\bandeira;

use \PDO as PDO;
use \Exception as Exception;

use app\bandeira\Bandeira as Bandeira;
use app\util\DataBase as DataBase;

class BandeiraDao {

    public function insert(Bandeira $bandeira) {
        $sql = "
            INSERT INTO bandeira (nome, url)
            VALUES (:nome, :url)
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $bandeira->getNome());
        $stmt->bindValue(':url', $bandeira->getUrl());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
        
    }

    public function get(Bandeira $bandeira) {
        $sql = "
            SELECT * FROM bandeira
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $bandeira->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchObject('app\bandeira\Bandeira');
    }

    public function getAll() {
        $sql = "
            SELECT * FROM bandeira
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\bandeira\Bandeira');
    }

    public function delete(Bandeira $bandeira) {
        $sql = "
            DELETE FROM bandeira
            WHERE nome = :nome
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