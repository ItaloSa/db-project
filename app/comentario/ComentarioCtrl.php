<?php

namespace app\comentario;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\comentario\Comentario as Comentario;
use app\comentario\ComentarioDao as ComentarioDao;

class ComentarioCtrl {

    public function create($data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $comentario = $this->mountComentario($data);
            $comentarioDao = new ComentarioDao();
            $comentarioDao->insert($comentario);
            return $comentario;
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
            $comentarioDao = new ComentarioDao();
            $result = $comentarioDao->getAll();
            if (sizeof($result) > 0) {
                $comentarios = [];
                foreach($result as $comentario) {
                    $comentarios[] = $comentario->json();
                }
                return $comentarios;
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
            $comentario = $this->mountComentario($data);
            $comentario->setLogin($login);
            $comentarioDao = new ComentarioDao();
            $comentario = $comentarioDao->update($comentario);
            return $comentario;
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
