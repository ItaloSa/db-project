<?php

namespace app\posto;

class Bandeira {
    private $nome;
    private $url;

    public function __construct() {

    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getUrl(): string {
        return $this->url;
    }

    public function setUrl(string $url): void{
        $this->url = $url;
    }
}