<?php

namespace app\posto;

class Combustivel {
    private $nome;
    private $momento;
    private $valor;

    public function __construct() {

    }

    public function getNome(): string {
        return $this->nome;
    }
    
    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getMomento(): datetime {
        return $this->momento;
    }

    public function setMomento(datetime $momento): void {
        $this->momento = $momento;
    }
 
    public function getValor(): float {
        return $this->valor;
    }

    public function setValor(float $valor): void {
        $this->valor = $valor;
    }
}