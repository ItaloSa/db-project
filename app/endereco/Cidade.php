<?php

namespace app\endereco;

class Cidade {
    private $nome;
    private $estado;
    private $latitude;
    private $longitude;

    public function json() {
        return get_object_vars($this);
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function setEstado(string $estado) {
        $this->estado = $estado;
    }
    
    public function getLatitude() {
        return $this->latitude;
    }

    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }    
    
}