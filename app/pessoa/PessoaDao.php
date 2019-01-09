
<?php

namespace app\pessoa;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;

use app\usuario\Usuario as Usuario;
use app\tipoUsuario\TipoUsuario as TipoUsuario;

class PessoaDao{

    public function insert(Usuario $usuario) {
        $sql = "
            INSERT INTO pessoa (
                login,
                nome,
                endereco,
                usuarioLogin,
                bairroNome
            ) VALUES (
                :login,
                :nome,
                :endereco,
                :usuarioLogin,
                :bairroNome
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':login', $pessoa->getLogin());
        $stmt->bindValue(':nome', $pessoa->getNome());
        $stmt->bindValue(':endereco', $pessoa->getEndereco());
        $stmt->bindValue(':usuarioLogin', $pessoa->getUsuarioLogin());
        $stmt->bindValue(':bairroNome', $pessoa->getBairroNome());
        
        
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
    }

     public function get(Pessoa $pessoa) {
        $sql = "
            SELECT * FROM pessoa
            WHERE login = :login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $pessoa->getLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populateUsuario($result);
    }

    public function getAll() {
        $sql = "
            SELECT * FROM pessoa
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $pessoas = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($result as $data) {
            $pessoas[] = $this->populatePessoa($data);
        }
        return $pessoas;


    }

    public function update(Pessoa $pessoa) {
        $sql = "
            UPDATE pessoa SET 
                nome = :nome,
                endereco = :endereco,
                usuarioLogin = :usuarioLogin,
                bairroNome = :bairroNome,

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

    public function delete(Pessoa $pessoa) {
        $sql = "
            DELETE FROM pessoa
            WHERE login = :login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $pessoa->getLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }


    private function populateUsuario($data): Pessoa {
        $pessoa = new Pessoa();
        $pessoa>setLogin($data['login']);
        $pessoa->setNome($data['nome']);
        $pessoa->setEndereco($data['endereco']);
        $pessoa->setUsuarioLogin($data['usuarioLogin']);
        $pessoa->setBairroNome($data['bairroNome']);        
        return $pessoa;
    }





}