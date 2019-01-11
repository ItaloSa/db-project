<?php

namespace app\postoCombustivel;

use app\combustivel\Combustivel as Combustivel;
use app\posto\Posto as Posto;

class PostoCombustivel {
    private $posto;
    private $combustivel;

    public function json() {
        if (isset($this->posto)) {
            $this->posto = $this->posto->json();
        }
        if (isset($this->combustivel)) {
            $this->combustivel = $this->combustivel->json();
        }
        return get_object_vars($this);
    }

    public function setPosto(Posto $posto) {
        $this->posto = $posto;
    }

    public function getPosto(): Posto{
        return $this->posto;
    }

    public function getCombustivel(): Combustivel{
        return $this->combustivel;
    }

    public function setCombustivel(Combustivel $combustivel {
        $this->combustivel = $combustivel;
    }

    


}    








