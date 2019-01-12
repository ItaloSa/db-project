<?php

namespace app\comentario;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;

use app\comentario\Comentario as Comentario;
use app\pessoa\PessoaDao as PessoaDao;
use app\posto\PostoDao as PostoDao;

class ComentarioDao{

	public function insert(Comentario $comentario) {
        $sql = "
            INSERT INTO comentario (
                posto_cnpj,
                pessoa_login,
                momento
            ) VALUES (
                :posto_cnpj,
                :pessoa_login,
                :momento
            )
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $comentario);
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }
    }

    public function get(Comentario $comentario) {
        $sql = "
            SELECT * FROM comentario c
            LEFT JOIN posto_completo pc
                ON c.posto_cnpj = pc.cnpj
            LEFT JOIN pessoa_completa pec
                ON c.pessoa_login = pec.login
            WHERE pessoa_login = :pessoa_login
            AND posto_cnpj = :posto_cnpj
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':pessoa_login', $comentario->getPessoa()->getLogin());
        $stmt->bindValue(':posto_cnpj', $comentario->getPosto()->getCnpj());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populateComentario($result);
    }

    public function getAll() {
        $sql = "
            SELECT * FROM comentario c
            LEFT JOIN posto_completo pc
                ON c.posto_cnpj = pc.cnpj
            LEFT JOIN pessoa_completa pec
                ON c.pessoa_login = pec.login
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

    public function update($pessoaLogin, $postoCnpj, Comentario $comentario) {
        $sql = "
            UPDATE comentario SET 
                posto_cnpj = :posto_cnpj,
                pessoa_login = :pessoa_login,
                momento = :momento
            WHERE posto_cnpj = :posto_cnpj_old
            AND pessoa_login = :pessoa_login_old
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt = $this->bindValues($stmt, $comentario);

        $stmt->bindValue(':posto_cnpj_old', $postoCnpj);
        $stmt->bindValue(':pessoa_login_old', $pessoaLogin);
                
        $result = $stmt->execute();
        $comentario = $this->get($comentario);
        return $comentario;
    }

    public function delete(Comentario $comentario) {
        $sql = "
            DELETE FROM comentario
            WHERE posto_cnpj = :posto_cnpj
            AND pessoa_login = :pessoa_login
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':posto_cnpj', $comentario->getPosto()->getCnpj());
        $stmt->bindValue(':pessoa_login', $comentario->getPessoa()->getLogin());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    public function bindValues($stmt, Comentario $comentario) {
        $stmt->bindValue(':posto_cnpj', $comentario->getPosto()->getCnpj());
        $stmt->bindValue(':pessoa_login', $comentario->getPessoa()->getLogin());
        $stmt->bindValue(':momento', $comentario->getMomento());
        return $stmt;
    }

    private function populateComentario($data): Comentario {
        $comentario = new Comentario();
        $comentario->setMomentoFromBanco($data['momento']);
        $postoDao = new PostoDao();
        $posto = $postoDao->populatePosto($data);
        $comentario->setPosto($posto);
        $pessoaDao = new PessoaDao();
        $pessoa = $pessoaDao->populatePessoa($data);
        $comentario->setPessoa($pessoa);
        return $comentario;
    }


}
