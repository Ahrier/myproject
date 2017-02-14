<?php
namespace app\ctrl;
class indexCtrl extends \core\myProject{
    public function index(){
        $model = new \app\model\ceshiModel();
        $data = $model->selectAll();
        $path = $_SERVER[REQUEST_URI];
        $this->assign('path',$path);
        $this->assign('data',$data);
        $this->display('index');
    }
    public function pass(){
        echo 111;
    }
} 