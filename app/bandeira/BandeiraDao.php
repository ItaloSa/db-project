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
        if ($bandeira->getUrl() != null) {
            $stmt->bindValue(':url', $bandeira->getUrl());
        } else {
            $stmt->bindValue(':url', null);
        }

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

    public function update($nome, Bandeira $bandeira) {
        $sql = "
            UPDATE bandeira SET 
                nome = :nome,
                url = :url
            WHERE nome = :nome_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $bandeira->getNome());
        if ($bandeira->getUrl() != null) {
            $stmt->bindValue(':url', $bandeira->getUrl());
        } else {
            $stmt->bindValue(':url', null);
        }
        $stmt->bindValue(':nome_old', $nome);
        $stmt->execute();
        $bandeira = $this->get($bandeira);
        return $bandeira;
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