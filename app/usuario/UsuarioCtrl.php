<?php

namespace app\usuario;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\usuario\Usuario as Usuario;
use app\usuario\UsuarioDao as UsuarioDao;

class UsuarioCtrl{

	public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $Usuario = new Usuario();
            $Usuario->setLogin($data['login']);
            $Usuario->setSenha($data['senha']);
            $Usuario->setTipoUsuario($data['tipoUsuario']);
            $UsuarioDao = new UsuarioDao();
            $UsuarioDao->insert($Usuario);
            return $Usuario;
        } catch (Error $e) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Some data is missing");
        } catch (Exception $e ) {
            if ($e->getCode() == "23000") {
                throw new Exception("Duplicate entry");
            } else {
                Registry::log()->error($e->getMessage());
                throw new Exception("Problems with Database");
            }
        }
    }

    public function getAll() {
        try {
            $UsuarioDao = new UsuarioDao();
            $result = $UsuarioDao->getAll();
            if (sizeof($result) > 0) {
                $usuarios = [];
                foreach($result as $usuario) {
                    $usuarios[] = $usuario->json();
                }
                return $suarios;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function delete($login) {
        if ($login == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $Usuario = new Usuario();
            $Usuario->setLogin($login);
            $UsuarioDao = new UsuarioDao();
            return $UsuarioDao->delete($Usuario);
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
