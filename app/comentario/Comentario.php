<?php

namespace app\comentario;



class Comentario {

	private posto_cnpj;
	private pessoa_login;
	private combustivel_nome;

	public function json() {

        return get_object_vars($this);
    }

    public function getPostoCnpj(): string {
        return $this->posto_cnpj;
    }

    public function setPostoCnpj(string $posto_cnpj) {
        $this->posto_cnpj = $posto_cnpj;
    }

    public function getPessoaLogin(): string {
        return $this->pessoa_login;
    }

    public function setPessoaLogin(string $pessoa_login) {
        $this->pessoa_login = $pessoa_login;
    }

    public function getCombustivelNome(): string {
        return $this->combustivel_nome;
    }

    public function setCombustivelNome(string $combustivel_nome) {
        $this->combustivel_nome = $combustivel_nome;
    }







}	
