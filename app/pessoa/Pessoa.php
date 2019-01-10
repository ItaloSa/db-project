<?php

namespace app\pessoa;


use app\usuario\Usuario as Usuario;
use app\endereco\Bairro as Bairro;

class Pessoa {
    private $login;
    private $nome;
    private $endereco;
    private $usuario;
    private $bairro;

    public function json() {
        if (isset($this->usuario)) {
            $this->usuario = $this->usuario->json();
        }
        if (isset($this->bairro)) {
            $this->bairro = $this->bairro->json();
        }
        return get_object_vars($this);
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function setLogin(string $login) {
        $this->login = $login;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
    }

    public function getEndereco(): string {
        return $this->endereco;
    }

    public function setEndereco(string $endereco) {
        $this->endereco = $endereco;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro(Bairro $bairro) {
        $this->bairro = $bairro;
    }

}
