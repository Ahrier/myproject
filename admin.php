<?php
/**
 * 后台入口文件
 * 1.定义常量
 * 2.加载函数库
 * 3.启动框架
 */
define('MYPROJECT',realpath('./'));    //当前框架所在目录
define('CORE',MYPROJECT.'/core');      //框架核心文件目录
define('APP',MYPROJECT.'/admin');        //框架项目文件目录
define('MODULE','admin');                //MVC模块
define('CONF',MYPROJECT.'/conf');      //数据库配置文件
define('URL','admin.php/');            //数据库配置文件

define('DEBUG',true);                  //是否开启调试模式
if(DEBUG){
    ini_set('display_error',On);
}else{
    ini_set('display_error',Off);
}

include CORE.'/common/function.php';   //加载公共函数库
include CORE.'/myProject.php';         //加载核心文件

//当实例化类时，当类不存在执行此方法
spl_autoload_register('\core\myProject::load');

\core\myProject::run();