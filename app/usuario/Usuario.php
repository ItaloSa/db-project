<?php

namespace app\usuario;

use app\tipoUsuario\TipoUsuario as TipoUsuario;

class Usuario {

    private $login;
    private $senha;
    private $tipoUsuario;

    public function getLogin(): string {
        return $this->login;
    }

    public function setLogin(string $login) {
        $this->login = $login;
    }

    public function getSenha(): string {
        return $this->senha;
    }

    public function setSenha(string $senha) {
        $this->senha = $senha;
    }

    public function getTipoUsuario(): TipoUsuario {
        return $this->tipoUsuario;
    }

    public function setSenha(TipoUsuario $tipoUsuario) {
        $this->tipoUsuario = $tipoUsuario;
    }

}