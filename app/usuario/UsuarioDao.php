<?php

namespace app\endereco;

use \PDO as PDO;
use \Exception as Exception;

use app\usuario\Usuario as Usuario;

use app\tipoUsuario\TipoUsario as TipoUsuario;

class UsuarioDao{

	public function insertUsuario(Usuario $usuario) {
        $sql = "
            INSERT INTO usuario (
                login,
                senha,
                tipoUsuario
            ) VALUES (
                :login,
                :senha,
                :tipoUsuario
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':login', $usuario->getLogin());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':tipoUsuario', $usuario->getTipoUsuario());
        

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }


    }

    

    public function getAllUsuarios() {
        $sql = "
            SELECT * FROM usuario
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'app\usuario\Usuario');
    }

    public function updateUsuario(Usuario $usuario) {
        $sql = "
            UPDATE usuario SET 
                senha = :senha,
                tipoUsuario = :tipoUsuario
            WHERE login = :login
            
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $usuario->getLogin());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':tipoUsuario', $usuario->getTipoUsuario());
        
        
        $stmt->execute();
        $usuario = $this->getUsuario($usuario);
        return $usuario;
    }

    public function deleteUsuario(Usuario $usuario) {
        $sql = "
            DELETE FROM usuario
            WHERE login = :login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $usuario->getLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }



}


