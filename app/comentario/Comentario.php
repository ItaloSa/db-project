<?php

namespace app\comentario;

use app\posto\Posto as Posto;
use app\pessoa\Pessoa as Pessoa;
use app\combustivel\Combustivel as Combustivel;
class Comentario {

	private $posto;
	private $pessoa;
	private $momento;

	public function json() {
        $momento = json_encode($this->momento);
        $momento = json_decode($momento);
        $this->momento = $momento->date;
        $this->posto = $this->posto->json();
        $this->pessoa = $this->pessoa->json();
        return get_object_vars($this);
    }

    public function getPosto(): Posto {
        return $this->posto;
    }

    public function setPosto(Posto $posto) {
        $this->posto = $posto;
    }

    public function getPessoa(): Pessoa {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }

    public function getMomento() {
        return $this->momento;
    }

    public function setMomento($momento) {
        $dto = \DateTime::createFromFormat(\DateTime::ATOM, $momento);
        $formattedDate = $dto->format('Y-m-d H:i:s');
        $this->momento = $formattedDate;
    }

    public function setMomentoFromBanco($momento) {
        $dt = new \DateTime($momento);
        $this->momento = $dt;
    }

}	
