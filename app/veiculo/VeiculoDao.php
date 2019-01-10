<?php
namespace app\veiculo;

use \PDO as PDO;
use \Exception as Exception;

use app\util\DataBase as DataBase;
use app\veiculo\Veiculo as Veiculo;

class VeiculoDao{


	public function insert(Veiculo $veiculo) {

        $sql = "
            INSERT INTO veiculo (
                placa,
                marca,
                modelo,
                pessoaLogin
            ) VALUES (
                :placa,
                :marca,
                :modelo,
                :pessoaLogin
            )
        ";
        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':placa', $veiculo->getPlaca());
        $stmt->bindValue(':marca', $veiculo->getMarca());
        $stmt->bindValue(':modelo', $veiculo->getModelo());
        $stmt->bindValue(':pessoaLogin', $veiculo->getPessoaLogin());
        
        
        $stmt->execute();

        if ($stmt->rowCount() < 1) {
            throw new Exception("Can't create");
        }

    }


    public function get(Veiculo $veiculo) {

        $sql = "
            SELECT * FROM veiculo
            WHERE login = :login
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':placa', $veiculo->getPlaca());

        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->populateVeiculo($result);
    }

    public function getAll() {

        $sql = "
            SELECT * FROM veiculo
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->execute();
        
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }

        $veiculos = [];
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($result as $data) {
            $veiculos[] = $this->populateVeiculo($data);
        }

        return $veiculos;
    }


    public function update(Veiculo $veiculo) {

        $sql = "
            UPDATE veiculo SET 
                placa = :placa,
                marca = :marca,
                modelo = :modelo,
                pessoaLogin = :pessoaLogin
            WHERE placa = :placa

            
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':placa', $veiculo->getPlaca());
        $stmt->bindValue(':marca', $uveiculo->getMarca());
        $stmt->bindValue(':modelo', $veiculo->getModelo());
        $stmt->bindValue(':pessoaLogin', $veiculo->getPessoaLogin());
        
        
        $stmt->execute();
        $veiculo = $this->get($veiculo);
        return $veiculo;
    }

    public function delete(Veiculo $veiculo) {

        $sql = "
            DELETE FROM veiculo
            WHERE placa = :placa
        ";

        $dataBase = DataBase::getInstance();
        $stmt = $dataBase->prepare($sql);
        $stmt->bindValue(':placa', $veiculo->getPlaca());
        $stmt->execute();
        if ($stmt->rowCount() < 1) {
            throw new Exception("Not found", 404);
        }
    }

    private function populateVeiculo($data): Veiculo {
        $veiculo = new Veiculo();
        $veiculo->setPlaca($data['placa']);
        $veiculo->setMarca($data['marca']);
        $veiculo->setModelo($data['modelo']);
        $veiculo->setPessoaLogin($data['pessoaLogin']);
        return $veiculo;
    }








}

