<?php
namespace Teacher\Controller;
use Think\Controller;
class LoginController extends Controller {
   //  public function index(){
   //      $paper_teacher=D('paper_teacher');
   //      if(IS_POST){
   //          if($paper_teacher->create($_POST,4)){
   //             if($paper_teacher->login()){
   //                    $this->success('登录成功，跳转中...',U('Index/index'));
   //          }else{
   //              $this->error('您的用户名或密码错误！');

   //          }
   //          }else{
   //              $this->error($paper_teacher->getError());
   //          }
   //          return;

   //      }
   //      if(session('id')){
   //          $this->error('您已经登陆该系统，请勿重复登陆！',U('Index/index'));
   //      }else{



   //     $this->display('login'); 
   // }
   //  }
    public function index(){
        $this->display();
    }

    public function Login_check( ) {
        $username = I('username');
        $password = substr(md5(I('password')), 0 , 10) ;
        $model = M('paper_teacher');
        $category  = $model ->where(array('id' => $username)) -> find();
        // 获取此人的系部
        $model2 = M('paper_system');

        $department  = $model2 ->where(array('id' => $category['sid'])) -> find();
        // 判断密码
        if ($password == $category['password'])
        {
            //登录成功写入session
            session('id',$category['id']);
            $paper_teacher=D('paper_teacher');
         $list=$paper_teacher->where(array('id' => session('id')))->find();
         //var_dump($list);
        // session('id',$list['id']); 
          //var_dump($list['id']);                                       //写入session
            session('username',$list['username']);
            //var_dump($list['username']);
        session('member',$list['member']);
        //var_dump($list['member']);
            if ($kind!='paper_admin') {
                session('category.department',$department);
            }
            $this -> success("登录成功",U('Examine/lst'));
        }
        else{
            $this -> error('账号或密码错误');
        }
    }       
    public function verify(){
        $Verify =     new \Think\Verify();
        $Verify->fontSize = 30;
        $Verify->length   = 3;
        $Verify->useNoise = false;
        $Verify->entry();
    }
}