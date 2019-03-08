<?php
namespace Teacher\Controller;
use Think\Controller;
class ResultsIndexController extends BaseController {
    public function lst(){
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
    public function edit(){
        $paper_student=D('paper_student');                                     // 实例化paper_student对象               
        if(IS_POST){
        	$data['id']=I('id');                   
            // $data['name']=I('name');             
            $data['score1']=I('score1');                  
            $data['score2']=I('score2');         
            if($paper_student->create($data)){                         // 根据表单提交的POST数据创建数据对象
                $save=$paper_student->save();
                if($save!==false){                             // 写入数据到数据库
                $this->success('上传开提成绩成功!',U('lst'));    //添加成功跳转
                    }else{
                        $this->error('上传开提成绩家失败!');
                    }
            }else{
                $this->error($paper_student->getError());
            }
            return;                                             //返回，不再出现页面

        }
        $paper_student=$paper_student->find(I('id'));
        $this->assign('paper_student',$paper_student);
        $this->display();
    }

}