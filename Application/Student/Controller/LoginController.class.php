<?php
namespace Student\Controller;
use Think\Controller;
class LoginController extends Controller {
	// 构造函数判断登录类别
	//public function Login_in(  ) {
	// $kind=$_GET['kind'];
	// 	switch ( $kind ) {
	// 		case 'admin':
	// 			$this -> System_login();
	// 			break;

	// 		case 'student':
	// 			$this -> Student_login();
	// 			break;

	// 		case 'teacher':
	// 			$this -> Teacher_login();
	// 			break;

	// 		case 'leader':
	// 			$this -> Leader_login();
	// 			break;
			
	// 		default:
	// 			exit('500');
	// 			break;
	// 	}
	//}
	// private function System_login() {
	// 	$this -> display('Login/systemload');
	// }
	// private function Student_login() {
	// 	$this -> display('Login/studentload');
	// }	
	// private function Teacher_login() {
	// 	$this -> display('Login/teacherload');
	// }	
	// private function Leader_login() {
	// 	$this -> display('Login/leaderload');
	// }
	// public function Login_check( ) {
	// 	$kind=$_GET['kind']; //判断是谁登录  kind是相应的表名
	// 	$username = I('username');
	// 	$password = substr(md5(I('password')), 0 , 10) ;
	// 	$model = M($kind);
	// 	$category  = $model ->where(array('id' => $username)) -> find();
	// 	// 获取此人的系部
	// 	$model2 = M('paper_system')->field('name');
	// 	$department  = $model2 -> select($category['sid']);
	// 	// 判断密码
	// 	if ($password == $category['password'])
	// 	{
	// 		//登录成功写入session
	// 		session('category.Id',$category['id']);
	// 		session('category.Name',$category['name']);
	// 		if ($kind!='paper_admin') {
	// 			session('category.department',$department);
	// 		}
	// 		/*此地方需要改成相应的登录位置switch*/
	// 		$this -> success("登录成功",U('Student/index'));
	// 	}
	// 	else{
	// 		$this -> error('账号或密码错误');
	// 	}
	// }		
	public function index(){
		$this->display();
	}

	public function Login_check( ) {
		$username = I('username');
		$password = substr(md5(I('password')), 0 , 10) ;
		$model = M('paper_student');
		$category  = $model ->where(array('id' => $username)) -> find();
		// 获取此人的系部
		$model2 = M('paper_system');

		$department  = $model2 ->where(array('id' => $category['sid'])) -> find();
		// 判断密码
		if ($password == $category['password'])
		{
			//登录成功写入session
			session('category.Id',$category['id']);
			session('category.Name',$category['name']);
			if ($kind!='paper_admin') {
				session('category.department',$department);
			}
			/*此地方需要改成相应的登录位置switch*/
			$this -> success("登录成功",U('Student/index'));
		}
		else{
			$this -> error('账号或密码错误');
		}
	}		
	
}