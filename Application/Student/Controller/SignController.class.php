<?php
namespace Student\Controller;
use Think\Controller;
class SignController extends BaseController {
	public function index() {
		$this -> display();
	}
	public function sign_in() {
		$model = M('paper_student');
		$username=I('username');
		$password=substr(md5( I('password')),0,10);
		$array = array(
				'id'   => '11',
				'name' => $username,
				'password'=>$password,
				'sid'  => '1',
			);
		if($model -> add($array)){
			$this ->success('成功',U('Login/Login_in?kind=teacher'));
		}else {
			$this -> error();
		}
	}
}