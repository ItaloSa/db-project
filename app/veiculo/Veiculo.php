<?php

namespace app\veiculo;

use app\pessoa\Pessoa as Pessoa;

class Veiculo {
    private $placa;
    private $marca;
    private $modelo;
    private $pessoa;

    public function json() {
        $this->pessoa = $this->pessoa->json();
        return get_object_vars($this);
    }

    public function getPlaca() {
        return $this->placa;
    }

    public function setPlaca(string $placa) {
        $this->placa = $placa;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function setMarca(string $marca) {
        $this->marca = $marca;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function setModelo(string $modelo) {
        $this->modelo = $modelo;
    }

    public function getPessoa(): Pessoa {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }







}