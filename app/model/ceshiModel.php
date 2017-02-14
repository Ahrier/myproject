<?php
namespace app\model;

class ceshiModel extends \core\lib\model{
    const TABLE = "test";
    protected static $list = array();
    
    public function __construct(){
        parent::__construct();
    }
    public function selectAll(){
        $sql = 'select * from '.self::TABLE;
        $rs = $this->query($sql);
        while($row = $rs -> fetch()){
            self::$list[]=$row;
        }
        return self::$list;
    }
}
