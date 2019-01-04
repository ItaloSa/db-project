<?php

namespace app\bandeira;

class Bandeira {
    private $nome;
    private $url;

    public function __construct() {

    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function setUrl(string $url){
        $this->url = $url;
    }

    public function json() {
        return get_object_vars($this);
    }

}