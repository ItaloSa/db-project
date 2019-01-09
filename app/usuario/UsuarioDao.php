<?php

namespace app\usuario;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;

use app\usuario\Usuario as Usuario;
use app\tipoUsuario\TipoUsuario as TipoUsuario;

class UsuarioDao{

	public function insert(Usuario $usuario) {
        $sql = "
            INSERT INTO usuario (
                login,
                senha,
                tipo_usuario_nome
            ) VALUES (
                :login,
                :senha,
                :tipo_usuario_nome
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':login', $usuario->getLogin());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':tipo_usuario_nome', $usuario->getTipoUsuario()->getNome());
        

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }


    }

    public function get(Usuario $usuario) {
        $sql = "
            SELECT * FROM usuario
            WHERE login = :login
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $usuario->getLogin());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populateUsuario($result);
    }

    public function getAll() {
        $sql = "
            SELECT * FROM usuario
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }

        $usuarios = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $data) {
            $usuarios[] = $this->populateUsuario($data);
        }

        return $usuarios;
    }

    public function update(Usuario $usuario) {
        $sql = "
            UPDATE usuario SET 
                senha = :senha,
                tipo_usuario_nome = :tipo_usuario_nome
            WHERE login = :login
            
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $usuario->getLogin());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':tipo_usuario_nome', $usuario->getTipoUsuario()->getNome());
        
        
        $stmt->execute();
        $usuario = $this->get($usuario);
        return $usuario;
    }

    public function delete(Usuario $usuario) {
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

    private function populateUsuario($data): Usuario {
        $usuario = new Usuario();
        $usuario->setLogin($data['login']);
        $usuario->setSenha($data['senha']);
        $tipoUsuario = new TipoUsuario();
        $tipoUsuario->setNome($data['tipo_usuario_nome']);
        $usuario->setTipoUsuario($tipoUsuario);
        return $usuario;
    }

}


