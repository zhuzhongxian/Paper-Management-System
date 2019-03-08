<?php
namespace Leader\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
    	$user = session('Leader');
        $deptment = session('Dept');
    	$this->assign('leader',$user);
    	$this->assign('deptname',$deptment['name']);
         $this->display();
    }
}