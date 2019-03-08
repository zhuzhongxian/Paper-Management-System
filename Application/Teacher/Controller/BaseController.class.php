<?php
namespace Teacher\Controller;
use Think\Controller;
class BaseController extends Controller{

    //定义全局变量leader，储存系主任session
    public $leader;
	 public function _initialize(){
        //判断用户是否登录
        $isLogin = $this->isLogin();
        if(!$isLogin){
            return $this->redirect('login/index');
        }
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
            $this->leader = session('id');

        }
        return $this->leader;
     }

}

?>