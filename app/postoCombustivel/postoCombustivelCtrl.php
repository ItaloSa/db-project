<?php

namespace app\postoCombustivel;

use \Exception as Exception;
use \Error as Error;
use Monolog\Registry as Registry;

use app\postoCombustivel\PostoCombustivel as PostoCombustivel;
use app\postoCombustivel\PostoCombustivelDao as PostoCombustivelDao;
use app\posto\PostoCtrl as PostoCtrl;
use app\posto\Posto as Posto;
use app\combustivel\CombustivelCtrl as CombustivelCtrl;
use app\combustivel\Combustivel as Combustivel;

class PostoCombustivelCtrl{

	public function create($data): PostoCombustivel {
		if($data == null){
			throw new Exception("Data can't be empty");				
        }
        
		try{
			$postoCombustivel = $this->mountPostoCombustivel($data);
			$postoCombustivelDao = new PostoCombustivelDao();
			$postoCombustivelDao->insert($postoCombustivel);
			return $postoCombustivel;
		}catch (Error $e) {
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

	public function get($posto): PostoCombustivel {
        if ($posto == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $postoCombustivel = new PostoCombustivel();
            $postoCombustivel->setPosto($posto);
            $postoCombustivelDao = new PostoCombustivelDao();
            return $postoCombustivelDao->get($postoCombustivel);
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
            $postoCombustivelDao = new PostoCombustivelDao();
            $result = $postoCombustivelDao->getAll();
            if (sizeof($result) > 0) {
                $postosCombustiveis = [];
                foreach($result as $posto) {
                    $postosCombustiveis[] = $posto->json();
                }
                return $postosCombustiveis;
            } else {
                throw new Exception("Nothing found", 404);
            }
        } catch (Exception $e ) {
            Registry::log()->error($e->getMessage());
            throw new Exception("Problems with Database");
        }
    }

    public function update($combustivelNome, $postoCnpj, $data) {
        if ($data == null) {
            throw new Exception("Data can't be empty");
        }

        try {
            $postoCombustivel = $this->mountPostoCombustivel($data);
            $postoCombustivelDao = new PostoCombustivelDao();
            $postoCombustivel = $postoCombustivelDao->update($combustivelNome, $postoCnpj, $postoCombustivel);
            return $postoCombustivel;
        } catch (Error $e) {
            var_dump($e); die();
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

    public function delete($posto) {
        if ($posto == null) {
            throw new Exception("Data can't be empty");
        }
        try {
            $postoCombustivel = new PostoCombustivel();
            $postoCombustivel->setPosto($posto);
            $postoCombustivelDao = new PostoCombustivelDao();
            return $postoCombustivelDao->delete($postoCombustivel);
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

    public function mountPostoCombustivel($data): PostoCombustivel {
        $postoCombustivel = new PostoCombustivel();

        $postoCtrl = new PostoCtrl();
        $posto = $postoCtrl->mountPosto($data['posto']);
        $postoCombustivel->setPosto($posto);

        $combustivel = new Combustivel();
        $combustivel->setNome($data['combustivel']['nome']);			
        $postoCombustivel->setCombustivel($combustivel);
		
		return $postoCombustivel;
    }










}