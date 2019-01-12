<?php

namespace app\abastecido;

use app\combustivel\Combustivel as Combustivel;
use app\veiculo\Veiculo as Veiculo;

class Abastecido{

	private $combustivel;
    private $veiculo;

    public function json() {
        if (isset($this->combustivel)) {
            $this->combustivel = $this->combustivel->json();
        }
        if (isset($this->veiculo)) {
            $this->veiculo = $this->veiculo->json();
        }
        return get_object_vars($this);
    }

    public function getCombustivel(): Combustivel {
        return $this->combustivel;
    }

    public function setCombustivel(Combustivel $combustivel) {
        $this->combustivel = $combustivel;
    }

    public function getVeiculo(): Veiculo {
        return $this->veiculo;
    }

    public function setVeiculo(Veiculo $veiculo) {
        $this->veiculo = $veiculo;
    }

}
