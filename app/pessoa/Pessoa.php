<?php

namespace app\pessoa;


use app\usuario\Usuario as Usuario;

use app\endereco\Endereco as Endereco;

class Pessoa {
    private $login;
    private $nome;
    private $endereco;
    private $usuarioLogin;
    private $bairroNome;

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

    public function getUsuarioLogin(): string {
        return $this->usuarioLogin;
    }

    public function setUsuarioLogin(string $usuarioLogin) {
        $this->usuarioLogin = $usuarioLogin;
    }

    public function getBairroNome(): string {
        return $this->bairroNome;
    }

    public function setBairroNome(string $bairroNome) {
        $this->bairroNome = $bairroNome;
    }





}




