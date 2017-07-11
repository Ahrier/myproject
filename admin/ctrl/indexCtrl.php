<?php
namespace admin\ctrl;
class indexCtrl extends \core\myProject{
    public function index(){
        echo 111;
        $model = new \admin\model\ceshiModel();
        $data = $model->selectAll();
        $this->assign('data',$data);
        $this->display('index');

    }
} 