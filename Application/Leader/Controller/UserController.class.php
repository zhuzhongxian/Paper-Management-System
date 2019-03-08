<?php
namespace Leader\Controller;
use Think\Controller;
class UserController extends BaseController {
    public function revise(){
         $this->display();
    }
    //修改密码
    public function revisecheck(){
    	$password = substr(md5(I('password')), 0 , 10);
    	$npassword = I('npassword');
    	$tpassword = I('tpassword');
    	$user = session('Leader');
    	if($password!=$user['password']){
    		$this->error('原密码不正确',U('user/revise'));
    	}
    	if($npassword!=$tpassword){
    		$this->error('两次新密码不一致',U('user/revise'));
    	}
        $npassword = substr(md5($npassword),0,10);
    	$rel = M('Leader')->where(array('id'=>$user['id']))->save(array('password'=>$npassword));
    	if($rel){
    		$this->success('密码修改成功，请重新登录',U('login/login'));
    	}else{
    		$this->error('密码修改失败',U('user/revise'));
    	}
    }
}