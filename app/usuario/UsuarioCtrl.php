<?php

namespace app\usuario;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\usuario\Usuario as Usuario;
use app\usuario\UsuarioDao as UsuarioDao;
use app\tipoUsuario\TipoUsuario as TipoUsuario;

class UsuarioCtrl {

    public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $usuario = $this->mountUsuario($data);
            $usuarioDao = new UsuarioDao();
            $usuarioDao->insert($usuario);
            return $usuario;
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
            $usuarioDao = new UsuarioDao();
            $result = $usuarioDao->getAll();
            if (sizeof($result) > 0) {
                $usuarios = [];
                foreach($result as $usuario) {
                    $usuarios[] = $usuario->json();
                }
                return $usuarios;
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

    public function update($login, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $usuario = $this->mountUsuario($data);
            $usuario->setLogin($login);
            $usuarioDao = new UsuarioDao();
            $usuario = $usuarioDao->update($usuario);
            return $usuario;
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

    public function delete($login) {
        if ($login == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $usuario = new Usuario();
            $usuario->setLogin($login);
            $usuarioDao = new UsuarioDao();
            return $usuarioDao->delete($usuario);
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

    private function mountUsuario($data): Usuario {
        $usuario = new Usuario();
        $usuario->setLogin($data['login']);
        $usuario->setSenha($data['senha']);
        if (isset($data['tipoUsuario'])) {
            $tipoUsuario = new TipoUsuario();
            $tipoUsuario->setNome($data['tipoUsuario']['nome']);
            $usuario->setTipoUsuario($tipoUsuario);
        }
        return $usuario;
    }

}
