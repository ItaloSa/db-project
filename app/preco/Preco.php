<?php

namespace app\preco;

use app\combustivel\Combustivel as Combustivel;
use app\posto\Posto as Posto;

class Preco{
	private $momento;
	private $valor;
	private $combustivel;
	private $posto;

	public function json() {
        if (isset($this->combustivel)) {
            $this->combustivel = $this->combustivel->json();
        }
        if (isset($this->posto)) {
            $this->posto = $this->posto->json();
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

    public function setPosto(Posto $posto) {
        $this->posto = $posto;
    }

    public function getPosto(): Posto{
        return $this->posto;
    }

    public function getCombustivel(): Combustivel {
        return $this->combustivel;
    }

    public function setCombustivel(Combustivel $combustivel {
        $this->combustivel = $combustivel;
    }









}
