<?php

namespace app\bandeira;

use \Exception as Exception;
use \Error as Error;
use app\bandeira\Bandeira as Bandeira;

class BandeiraCtrl {
    
    public function create($data): Bandeira {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bandeira = new Bandeira();
            $bandeira->setNome($data["nome"]);
            $bandeira->setUrl($data["url"]);
            return $bandeira;
        } catch(Error $e) {
            throw new Exception("Some data is missing");
        }

    }

}