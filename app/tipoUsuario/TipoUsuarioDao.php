<?php

namespace app\tipoUsuario;

use \PDO as PDO;
use \Exception as Exception;

use app\tipoUsuario\TipoUsuario as TipoUsuario;
use app\util\DataBase as DataBase;

class TipoUsuarioDao {
    public function insert(TipoUsuario $tipoUsuario) {
        $sql = "
            INSERT INTO tipo_usuario (nome)
            VALUES (:nome)
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':nome', $tipoUsuario->getNome());
       
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
    }

    public function get(TipoUsuario $tipoUsuario) {
        $sql = "
            SELECT * FROM tipo_usuario
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $tipoUsuario->getNome());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchObject('app\tipoUsuario\TipoUsuario');
    }

    public function getAll() {
        $sql = "
            SELECT * FROM tipo_usuario
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\tipoUsuario\TipoUsuario');
    }

    public function update($nome, TipoUsuario $tipoUsuario) {
        $sql = "
            UPDATE tipo_usuario SET 
				nome = :nome
            WHERE nome = :old_nome
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $tipoUsuario->getNome());        
        $stmt->bindValue(':old_nome', $nome);                
        $stmt->execute();
        $tipoUsuario = $this->get($tipoUsuario);
        return $tipoUsuario;
    }

    public function delete(TipoUsuario $tipoUsuario) {
        $sql = "
            DELETE FROM tipo_usuario
            WHERE nome = :nome
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':nome', $tipoUsuario->getNome());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

}