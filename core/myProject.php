<?php
namespace core;

class myProject{

    public static $classMap = array();
    public $assign;
    static public function run(){
        //项目入口
        $route = new \core\lib\route();
        $ctrlClass = $route->ctrl;
        $action = $route->act;
        $ctrlfile = APP.'/ctrl/'. $ctrlClass.'Ctrl.php';
        $cClass = '\\'.MODULE.'\ctrl\\'.$ctrlClass.'Ctrl';
        if(is_file($ctrlfile)){
            include $ctrlfile;
            $ctrl = new $cClass();
            $ctrl->$action();
        }else{
            throw new \Exception('找不到控制器'.$ctrlClass);
        }
    }
    static public function load($class){
        //自动加载类库
        //加载类原型 new \core\XXX类
        //$class需要加载的类 \core\XXX 需转化成 myProject.'/core/XXX类'
        if(isset($classMap[$class])){
            return true;
        }else{
            $class = str_replace('\\','/',$class);
            $file =MYPROJECT .'/'. $class . '.php';
            if(is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }
    public function assign($name,$value){
        $this->assign[$name] = $value;
    }
    public function display($file){
        $ctrl = str_replace('Ctrl','',end(explode('\\',get_called_class())));
        $file = APP."/view/".$ctrl."/".$file.".html";
        extract($this->assign);
        if(is_file($file)){
            include $file;
        }
    }
}