<?php

namespace app\comentario;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;

use app\comentario\Comentario as Comentario;

class ComentarioDao{

	public function insert(Comentario $comentario) {
        $sql = "
            INSERT INTO comentario (
                posto_cnpj,
                pessoa_login,
                combustivel_nome
            ) VALUES (
                :posto_cnpj,
                :pessoa_login,
                :combustivel_nome
            )
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);

        $stmt->bindValue(':posto_cnpj', $comentario->getPostoCnpj());
        $stmt->bindValue(':pessoa_login', $comentario->getPessoaLogin());
        $stmt->bindValue(':combustivel_nome', $comentario->getCombustivelNome);
        

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }


    }

    public function get(Comentario $comentario) {
        $sql = "
            SELECT * FROM comentario
            WHERE pessoa_login = :pessoa_login
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':pessoa_login', $comentario->getPessoaLogin());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populateComentario($result);
    }

    public function getAll() {
        $sql = "
            SELECT * FROM comentario
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }

        $comentarios = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $data) {
            $comentarios[] = $this->populateComentario($data);
        }

        return $comentarios;
    }

    public function update(Comentario $comentario) {
        $sql = "
            UPDATE comentario SET 
                posto_cnpj = :posto_cnpj,
                combustivel_nome = :combustivel_nome
            WHERE pessoa_login = :pessoa_login
            
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':pessoa_login', $comentario->getPessoaLogin());
        $stmt->bindValue(':senha', $comentario->getPostoCnpj());
        $stmt->bindValue(':combustivel_nome', $comentario->getCombustivelNome());
        
        
        $stmt->execute();
        $comentario = $this->get($comentario);
        return $comentario;
    }

    public function delete(Comentario $comentario) {
        $sql = "
            DELETE FROM comentario
            WHERE pessoa_login = :pessoa_login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':pessoa_login', $comentario->getPessoaLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    private function populateComentario($data): Comentario {
        $comentario = new Comentario();
        $comentario->setPostoCnpj($data['posto_cnpj']);
        $comentario->setPessoaLogin($data['pessoa_login']);
        $comentario->setCombustivelNome($data['combustivel_nome']);
        
        return $comentario;
    }


}
