<?php
namespace Teacher\Controller;
use Think\Controller;
class IndexController extends BaseController {
        public function index(){
        $paper_student=D('paper_student');
        $a=explode(',',session('member'),5);
        //var_dump($a);

        $m=array();
        $k=0;

        foreach ($a as $value) {
            //echo "$value";
              $value=substr($value,1);
             $lists[$k]=$paper_student->where("id = '".$value."'")->find();
             $k++;
        }
        $m=array($lists[0],$lists[1],$lists[2],$lists[3],$lists[4]);
        //var_dump($lists);
        $this->assign('list',$m);
        $this->display();
    }
       // public function download(){
       //  //接收id
       //  $id = I('paper_student.id');
       //  //查询数据
       //  $data = M('paper_student')->find($id);
       //  //下载代码
       //  $file = WORKING_PATH.$data['ppt1'];
       //  //输出文件
       //  header("Content-type: application/octet-stream");
       //  header('Content-Disposition: attachment; filename="'.basename($file) .'"');
       //  header("Content-Length: ".filesize($file));
       //  //输出缓冲区
       //  readfile($file);
       // }


//上传
    public function add(){
        $paper_student=D('paper_student');
        if(IS_POST){
           $data['id']=I('id');                   
            $data['name']=I('name');
            if($_FILES['ppt1']['tmp_name']!=''){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','doc','pptx');// 设置附件上传类型
                $upload->savePath  =      './Public/Uploads/'; // 设置附件上传目录
                $upload->rootPath  =      './'; // 设置附件上传目录
                $info   =   $upload->uploadOne($_FILES['ppt1']);
                if(!$info){
                    $this->error($upload->getError());
                }else{
                   $data['ppt1']=$info['savepath'].$info['savename'];
                }
            }
            if($paper_student->create($data)){
                $save=$paper_student->save();
                if($save !== false){
                    $this->success('修改成功！',U('index'));
                }else{
                    $this->error('修改失败！');
                }
            }else{
                $this->error($paper_student->getError());
            }

            return;
        }
       $paper_student=$paper_student->find(I('id'));
        $this->assign('paper_student',$paper_student);
        $this->display();
    }

}