<?php
  namespace Leader\Controller;
  use Think\Controller;
  class LoginController extends Controller{
  	public function login(){
  		$this->display();
  	}
  	public function logincheck(){
		$username = I('username');
		$password = substr(md5(I('password')), 0 , 10) ;
		$model = M('Leader');
		$category  = $model ->where(array('id' => $username)) -> find();
		// 判断密码
		if ($password == $category['password'])
		{
			//登录成功写入session
			session('Leader',$category);
            $deptment = M('System')->where(array('id'=>$category['sid']))->find();
            session('Dept',$deptment);
			/*此地方需要改成相应的登录位置switch*/
			$this -> success("登录成功",U('index/index'));
		}
		else{
			$this -> error('账号或密码错误');
		}
  	}
     public function loginout(){
     	session('Leader',null);
     	session('Dept',null);
     	return $this->redirect('login/login');
     }
  }
?>