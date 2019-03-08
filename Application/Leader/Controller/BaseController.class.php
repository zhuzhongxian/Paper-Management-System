<?php
namespace Leader\Controller;
use Think\Controller;
class BaseController extends Controller{

    //定义全局变量leader，储存系主任session
    public $leader;
	 public function _initialize(){
        //判断用户是否登录
        $isLogin = $this->isLogin();
        if(!$isLogin){
            return $this->redirect('login/login');
        }
        $user = session('Leader');
        $deptment = session('Dept');
        $this->assign('leader',$user);
        $this->assign('deptname',$deptment['name']);
     }

     //判断用户是否登录
     public function isLogin(){
        //获取session
        $user = $this->getLoginUser();
        if($user){
            return true;
        }
        return false;
     }

    //获取session
     public function getLoginUser(){
        if(!$this->leader){
            $this->leader = session('Leader');

        }
        return $this->leader;
     }

    //获取页数
	public function getPages($count,$pagesize=10){
           $Page       = new \Think\Page($count,$pagesize);// 实例化分页类 传入总记录数和每页显示的记录数
            $Page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $Page->setConfig('last', '末页');
            $Page->setConfig('first', '首页');
            $Page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
            $Page->lastSuffix = false;//最后一页不显示为总页数

            return $Page;
	}
}

?>