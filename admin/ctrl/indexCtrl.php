<?php
namespace admin\ctrl;
class indexCtrl extends \core\myProject{
    public function index(){
        $model = new \admin\model\ceshiModel();
        $data = $model->selectAll();
        $this->assign('data',$data);
        $this->display('index');

    }
} 