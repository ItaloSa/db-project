<?php

namespace app\usuario;

use app\tipoUsuario\TipoUsuario as TipoUsuario;

class Usuario {

    private $login;
    private $senha;
    private $tipoUsuario;

    public function json() {
        if (isset($this->tipoUsuario)) {
            $this->tipoUsuario = $this->tipoUsuario->json();
        }
        return get_object_vars($this);
    }

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

    public function getTipoUsuario() {
        return $this->tipoUsuario;
    }

    public function setTipoUsuario(TipoUsuario $tipoUsuario) {
        $this->tipoUsuario = $tipoUsuario;
    }

}