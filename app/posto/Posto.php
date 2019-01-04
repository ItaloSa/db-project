<?php

namespace app\posto;

use app\bandeira\Bandeira as Bandeira;

class Posto {
    private $cnpj;
    private $razaoSocial;
    private $nomeFantasia;
    private $latitude;
    private $longitude;
    private $endereco;
    private $telefone;
    private $bandeira;

    public function __construct() {

    }

    public function getCnpj(): string {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): void {
        $this->cnpj = $cnpj;
    }

    public function getRazaoSocial(): string {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(string $razaoSocial): void {
        $this->razaoSocial = $razaoSocial;
    }

    public function getNomeFantasia(): string {
        return $this->nomeFantasia;
    }

    public function setNomeFantasia(string $nomeFantasia): void {
        $this->nomeFantasia = $nomeFantasia;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }
 
    public function setLatitude(float $latitude): void {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }
 
    public function setLongitude(float $longitude): void {
        $this->longitude = $longitude;
    }
 
    public function getEndereco(): string {
        return $this->endereco;
    }
 
    public function setEndereco(string $endereco): void {
        $this->endereco = $endereco;
    }
 
    public function getTelefone(): string {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): void {
        $this->telefone = $telefone;
    }

    public function getBandeira(): Bandeira {
        return $this->bandeira;
    }

    public function setBandeira(Bandeira $bandeira): void {
        $this->bandeira = $bandeira;
    }

}