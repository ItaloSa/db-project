<?php

namespace app\preco;

use app\postoCombustivel\PostoCombustivel as PostoCombustivel;


class Preco{
	private $momento;
	private $valor;
	private $postoCombustivel;
	

	public function json() {
        if (isset($this->postoCombustivel)) {
            $this->postoCombustivel = $this->postoCombustivel->json();
        }
        
        return get_object_vars($this);
    }

    public function setMomento(string $momemto) {
        $this->momemto = $momemto;
    }

    public function getMomento(): string {
        return $this->momemto;
    }

    public function setValor(string $valor) {
        $this->valor = $valor;
    }

    public function getValor(): string {
        return $this->valor;
    }

    public function setPostoCombustivel(PostoCombustivel $postoCombustivel) {
        $this->postoCombustivel = $postoCombustivel;
    }

    public function getPostoCombustivel(): PostoCombustivel{
        return $this->postoCombustivel;
    }

    








}
