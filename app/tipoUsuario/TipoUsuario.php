<?php

namespace app\tipoUsuario;

class TipoUsuario {

    private $nome;

    public function json() {
        return get_object_vars($this);
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
    }

}