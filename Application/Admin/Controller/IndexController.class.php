<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
    	// session('id','1630090136');
    	// session('name','朱忠贤');
    	//$session=session();
    	//var_dump($session);die;
        $this->show();
    }// index end
    public function edit(){
    	if(IS_POST){
    		$post=I('post.');
    		if($post['password']){//判断是否修改密码
    			$post['password']=substr(md5($post['password']),0,10);//加密后截取10位做为密码
    			//var_dump($post['password']);die;
    		}else{//如果没有修改就获取隐藏域中的password1原密码
    			$post['password']=$post['password1'];
    		}
    		$values=D('Admin')->save($post);
    		if ($values!==false) {
                session(null);
    			$this->success('修改成功');
    		}else{
    			$this->error('修改失败');
    		}
    	}else{
    	//session('id','1630090136');
    	//session('name','朱忠贤');
    	$session=session();
    	//var_dump($session);die;
    	$model=D('Admin');
    	$data=$model->where('id='.$session['id'])->find();
    	//var_dump($data);die;
    	$this->assign('data',$data);
    	$this->show();
    }//else end
    }//edit end
    public function logout(){
    	//清除session
    	session(null);
    	//跳转到登录界面
    	$this->success('退出成功');
    }//logOut end
    public function downloads(){
        $name=$_GET['name'].'.xlsx';
        //var_dump($name);die;
        $url='Public/model/'.$name;
        import('Org.Net.Http');
            $http = new \Org\Net\Http;
            $http->download($url,$path);
    }//download
}