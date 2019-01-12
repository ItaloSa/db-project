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
            $bandeira = $this->mountBandeira($data);
            $bandeiraDao = new BandeiraDao();
            $bandeiraDao->insert($bandeira);
            return $bandeira;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->errorInfo[1] == 1452) {
                throw new Exception("Can't Create");
            } else if ($e->errorInfo[1] == 1062) {
                throw new Exception("Duplicate entry");
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
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
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }

    }

    public function getAll(): array {
        try {
            $bandeiraDao = new BandeiraDao();
            $result = $bandeiraDao->getAll();
            if (sizeof($result) > 0) {
                $bandeiras = [];
                foreach($result as $bandeira) {
                    $bandeiras[] = $bandeira->json();
                }
                return $bandeiras;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function delete($nome) {
        if ($nome == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $bandeira = new Bandeira();
            $bandeira->setNome($nome);
            $bandeiraDao = new BandeiraDao();
            return $bandeiraDao->delete($bandeira);
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }

    }

    public function mountBandeira($data): Bandeira {
        $bandeira = new Bandeira();
        $bandeira->setNome($data["nome"]);
        $bandeira->setUrl($data["url"]);
        return $bandeira;
    }

}