<?php

namespace app\bandeira;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\bandeira\Bandeira as Bandeira;
use app\bandeira\BandeiraDao as BandeiraDao;

class BandeiraCtrl {
    
    public function create($data): Bandeira {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bandeira = new Bandeira();
            $bandeira->setNome($data["nome"]);
            $bandeira->setUrl($data["url"]);
            $bandeiraDao = new BandeiraDao();
            $bandeiraDao->insert($bandeira);
            return $bandeira;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }

    }

    public function get($nome): Bandeira {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bandeira = new Bandeira();
            $bandeira->setNome($nome);
            $bandeiraDao = new BandeiraDao();
            return $bandeiraDao->get($bandeira);
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }

    }

}