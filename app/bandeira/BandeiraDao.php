<?php

namespace app\bandeira;

use app\bandeira\Bandeira as Bandeira;
use app\util\DataBase as DataBase;

class BandeiraDao {

    public function insert(Bandeira $bandeira) {
        try {
            $sql = "
                INSERT INTO bandeira (nome, url)
                VALUES (:nome, :url)
            ";

            $dataBase = DataBase::getInstance();
            $stmt = $dataBase->prepare($sql);

            $stmt->bindValue(':nome', $bandeira->getNome());
            $stmt->bindValue(':url', $bandeira->getUrl());

            $result = $stmt->execute();
            if ($result < 1) {
                throw new Exception("Can't create");
            }
        }  catch (Exception $e) {
            Monolog\Registry::log()->error('BandeiraDao', $e);
            throw new Exception("Some data is missing");
        }
    }
    
}