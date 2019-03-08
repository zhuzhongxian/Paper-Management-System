<?php
namespace Teacher\Controller;
use Think\Controller;
class AdminController extends BaseController {

    public function edit(){
    	$paper_teacher=D('paper_teacher');
         session('id');
         
         $list=$paper_teacher->where(array('id' => session('id')))->find();
         //var_dump($list);
         session('id',$list['id']); 
          //var_dump($list['id']);                                       //写入session
            session('username',$list['username']);
            //var_dump($list['username']);
        session('member',$list['member']);
        //var_dump($list['member']);
       
    	 if(IS_POST){
        	$data['username']=I('username');
        	$data['id']=I('id');
            $paper_teacher_s=$paper_teacher->find($data['id']);
            $password=$paper_teacher_s['password'];
            if(I('password')){
              $data['password']=md5(I('password'));
            }else{
                $data['password']=$password;
            }
        	if($paper_teacher->create($data)){
        		$save=$paper_teacher->save();
        		if($save!==false){
        		     $this->success('修改管理员成功!',U('Index/index'));
        	        }else{
        	        	$this->error('修改管理员失败!');
        	        }
        	}else{
        		$this->error($paper_teacher->getError());
        	}
        	return;
        }
    	$paper_teachers=$paper_teacher->find(I('id'));
    	$this->assign('paper_teacher',$paper_teachers);
        $this->display();
    }
   
    public function logout(){
       session(null); // 清空当前的session 
       $this->success('退出成功，跳转中...',U('Login/index'));
    }
}
