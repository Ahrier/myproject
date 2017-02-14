<?php
namespace core\lib;

class model extends \PDO{
    public function __construct(){
        $file = CONF."/config.ini.php";
        if(is_file($file)){
            include $file;
        }
        $dsn = DB.":host=".DB_HOST.";dbname=".DB_NAME;
        $username = DB_USER;
        $passwd = DB_PASS;
        try{
            parent::__construct($dsn, $username, $passwd);
        } catch (\PDOException $e){
            p($e->getMessage());
        }
    }
}