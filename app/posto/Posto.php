<?php

namespace app\posto;

use app\bandeira\Bandeira as Bandeira;
use app\endereco\Bairro as Bairro;

class Posto {
    private $cnpj;
    private $razaoSocial;
    private $nomeFantasia;
    private $latitude;
    private $longitude;
    private $endereco;
    private $telefone;
    private $bandeira;
    private $bairro;

    public function json() {
        if (isset($this->bandeira)) {
            $this->bandeira = $this->bandeira->json();
        }
        if (isset($this->bairro)) {
            $this->bairro = $this->bairro->json();
        }
        return get_object_vars($this);
    }

    public function getCnpj(): string {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj) {
        $this->cnpj = $cnpj;
    }

    public function getRazaoSocial(): string {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(string $razaoSocial) {
        $this->razaoSocial = $razaoSocial;
    }

    public function getNomeFantasia(): string {
        return $this->nomeFantasia;
    }

    public function setNomeFantasia(string $nomeFantasia) {
        $this->nomeFantasia = $nomeFantasia;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }
 
    public function setLatitude(float $latitude) {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }
 
    public function setLongitude(float $longitude) {
        $this->longitude = $longitude;
    }
 
    public function getEndereco(): string {
        return $this->endereco;
    }
 
    public function setEndereco(string $endereco) {
        $this->endereco = $endereco;
    }
 
    public function getTelefone(): string {
        return $this->telefone;
    }

    public function setTelefone(string $telefone) {
        $this->telefone = $telefone;
    }

    public function getBandeira(): Bandeira {
        return $this->bandeira;
    }

    public function setBandeira(Bandeira $bandeira) {
        $this->bandeira = $bandeira;
    }

    public function getBairro(): Bairro {
        return $this->bairro;
    }

    public function setBairro(Bairro $bairro) {
        $this->bairro = $bairro;
    }

}