<?php

namespace app\util;

use \PDO as PDO;

class DataBase {
   
    public static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO(
                getenv('DB_DSN'), 
                getenv('DB_USR'), 
                getenv('DB_PWD'),
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }
    
        return self::$instance;

    }
     
}