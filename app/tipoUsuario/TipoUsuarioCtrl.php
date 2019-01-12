<?php

namespace app\tipoUsuario;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\tipoUsuario\TipoUsuario as TipoUsuario;
use app\tipoUsuario\TipoUsuarioDao as TipoUsuarioDao;


class TipoUsuarioCtrl {

    public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->setNome($data['nome']);
            $tipoUsuarioDao = new TipoUsuarioDao();
            $tipoUsuarioDao->insert($tipoUsuario);
            return $tipoUsuario;
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

    public function getAll() {
        try {
            $tipoUsuarioDao = new TipoUsuarioDao();
            $result = $tipoUsuarioDao->getAll();
            if (sizeof($result) > 0) {
                $tiposUsuario = [];
                foreach($result as $tipoUsuario) {
                    $tiposUsuario[] = $tipoUsuario->json();
                }
                return $tiposUsuario;
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

    public function update($nome, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->setNome($data['nome']);
            $tipoUsuarioDao = new TipoUsuarioDao();
            $tipoUsuario = $tipoUsuarioDao->update($nome, $tipoUsuario);
            return $tipoUsuario;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == 404) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else if ($e->getCode() == 400) {
                throw new Exception($e->getMessage(), $e->getCode());
            } else  {
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
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->setNome($nome);
            $tipoUsuarioDao = new TipoUsuarioDao();
            return $tipoUsuarioDao->delete($tipoUsuario);
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

}