<?php
namespace Admin\Controller;
use Think\Controller;
class StudentController extends BaseController {
    public function showGrade(){
    	$model= D('Student');
    	$count=$model->count();
    	$page=new\Think\Page($count,20);
    	$page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','末页');//当分页页码数少于rollPage时不会显示首页和末页
        $page->setConfig('firest','首页');
    	$show=$page->show();
    	$data=$model->limit($page->firstRow,$page->listRows)->select();

    	//echo($data);die;
    	$this->assign('data',$data);
    	$this->assign('show',$show);
        $this->show();
    }
}