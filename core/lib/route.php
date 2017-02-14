<?php
namespace core\lib;
class route{
    public $ctrl;
    public $act;
    public  function __construct(){
        /**
         * 路由类获取URL参数部分，返回对应的控制器和方法盒GET传值
         */
        $path = $_SERVER[REQUEST_URI];
        if(isset($path) && $path!='/'){
            $patharr = explode('/',trim($path,'/'));
            if(isset($patharr[0])){
                $this->ctrl = $patharr[0];
            }
            unset($patharr[0]);
            if(isset($patharr[1])){
                $this->act = $patharr[1];
                unset($patharr[1]);
            }else{
                $this->act = 'index';
            }
            $count = count($patharr) + 2;
            $i = 2;
            while ($i<$count){
                $_GET[$patharr[$i]] = $patharr[$i + 1];
                $i += 2;
            }
        }else{
            $this->act = 'index';
            $this->ctrl = 'index';
        }
    }
}