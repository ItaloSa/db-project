<?php

namespace app\preco;

use app\postoCombustivel\PostoCombustivel as PostoCombustivel;


class Preco{
	private $momento;
	private $valor;
	private $postoCombustivel;
	

	public function json() {
        $momento = json_encode($this->momento);
        $momento = json_decode($momento);
        $this->momento = $momento->date;
        if (isset($this->postoCombustivel)) {
            $this->postoCombustivel = $this->postoCombustivel->json();
        }
        
        return get_object_vars($this);
    }

    public function getMomento() {
        return $this->momento;
    }

    public function setMomento($momento) {
        $momento = str_replace("Z", "", $momento);
        $date = new \DateTime($momento, new \DateTimeZone('UTC'));
        $this->momento = $date;
    }

    public function setMomentoFromBanco($momento) {
        $dt = new \DateTime($momento);
        $this->momento = $dt;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setPostoCombustivel(PostoCombustivel $postoCombustivel) {
        $this->postoCombustivel = $postoCombustivel;
    }

    public function getPostoCombustivel(): PostoCombustivel{
        return $this->postoCombustivel;
    }

}
