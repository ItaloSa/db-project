<?php

namespace app\pessoa;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;

use app\pessoa\Pessoa as Pessoa;
use app\usuario\Usuario as Usuario;
use app\endereco\Bairro as Bairro;
use app\endereco\Cidade as Cidade;
use app\tipoUsuario\TipoUsuario as TipoUsuario;

class PessoaDao {

    public function insert(Pessoa $pessoa) {
        $sql = "
            INSERT INTO pessoa (
                login,
                nome,
                endereco,
                usuario_login,
                bairro_nome
            ) VALUES (
                :login,
                :nome,
                :endereco,
                :usuario_login,
                :bairro_nome
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $pessoa->getLogin());
        $stmt = $this->bindValues($stmt, $pessoa);        
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
    }

     public function get(Pessoa $pessoa) {
        $sql = "
            SELECT 
                p.login,
                p.endereco,
                p.nome,
                p.usuario_login,
                p.bairro_nome,
                bc.cidade_nome,
                bc.cidade_estado,
                bc.cidade_latitude,
                bc.cidade_longitude,
                u.senha,
                u.tipo_usuario_nome
            FROM pessoa p
            JOIN bairro_cidade bc
                ON p.bairro_nome = bc.bairro_nome
            LEFT JOIN usuario u
                ON p.usuario_login = u.login
            LEFT JOIN tipo_usuario tu
                ON u.tipo_usuario_nome = tu.nome
            WHERE p.login = :login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':login', $pessoa->getLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populatePessoa($result);
    }

    public function getAll() {
        $sql = "
            SELECT 
                p.login,
                p.endereco,
                p.nome,
                p.usuario_login,
                p.bairro_nome,
                bc.cidade_nome,
                bc.cidade_estado,
                bc.cidade_latitude,
                bc.cidade_longitude,
                u.senha,
                u.tipo_usuario_nome
            FROM pessoa p
            JOIN bairro_cidade bc
                ON p.bairro_nome = bc.bairro_nome
            LEFT JOIN usuario u
                ON p.usuario_login = u.login
            LEFT JOIN tipo_usuario tu
                ON u.tipo_usuario_nome = tu.nome
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
                usuario_login = :usuario_login,
                bairro_nome = :bairro_nome
            WHERE login = :login
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $pessoa);                
        $stmt->execute();
        $pessoa = $this->get($pessoa);
        return $pessoa;
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

    private function populatePessoa($data): Pessoa {
        $pessoa = new Pessoa();
        $pessoa->setLogin($data['login']);
        $pessoa->setEndereco($data['endereco']);
        $pessoa->setNome($data['nome']);
        $bairro = new Bairro();
        $bairro->setNome($data['bairro_nome']);
        $cidade = new Cidade();
        $cidade->setNome($data['cidade_nome']);
        $cidade->setEstado($data['cidade_estado']);
        $cidade->setLatitude($data['cidade_latitude']);
        $cidade->setLongitude($data['cidade_longitude']);
        $bairro->setCidade($cidade);
        $pessoa->setBairro($bairro);
        if (isset($data['usuario_login'])) {
            $usuario = new Usuario();
            $usuario->setLogin($data['usuario_login']);
            $usuario->setSenha($data['senha']);
            if (isset($data['tipo_usuario_nome'])) {
                $tipoUsuario = new TipoUsuario();
                $tipoUsuario->setNome($data['tipo_usuario_nome']);
                $usuario->setTipoUsuario($tipoUsuario);
            }
            $pessoa->setUsuario($usuario);
        }
        return $pessoa;
    }

    private function bindValues($stmt, $pessoa) {
        $stmt->bindValue(':nome', $pessoa->getNome());
        $stmt->bindValue(':endereco', $pessoa->getEndereco());
        if ($pessoa->getUsuario() != null) {
            $stmt->bindValue(':usuario_login', $pessoa->getUsuario()->getLogin());
        } else {
            $stmt->bindValue(':usuario_login', null);
        }
        $stmt->bindValue(':bairro_nome', $pessoa->getBairro()->getNome());
        $stmt->bindValue(':login', $pessoa->getLogin());
        return $stmt;
    }
}