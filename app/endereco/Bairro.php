<?php

namespace app\endereco;

use app\endereco\Cidade as Cidade;

class Bairro {
    private $nome;
    private $cidade;
    
    public function __construct() {

    }

    public function json() {
        $this->cidade = $this->getCidade()->json();
        return get_object_vars($this);
    }

    public function getNome(): string {
        return $this->nome;
    }
    
    public function setNome(string $nome) {
        $this->nome = $nome;
    }

    public function getCidade(): Cidade {
        return $this->cidade;
    }
    
    public function setCidade(Cidade $cidade) {
        $this->cidade = $cidade;
    }

}