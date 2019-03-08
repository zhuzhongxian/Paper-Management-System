<?php
namespace Teacher\Controller;
use Think\Controller;
class ExamineController extends BaseController {
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
        //var_dump($m);exit;
        //var_dump($lists);
        $this->assign('list',$m);
        $this->display();
    }






      public function edit(){
        $paper_student=D('paper_student');
         if(IS_POST){
            $data['topic_state1']=I('topic_state1');
            session('aa',$data['topic_state1']);
            //var_dump($data['topic_state1']);
            if($paper_student->create()){
                if($paper_student->save()){

                    $this->success('操作成功',U('lst'));  
                }else{
                    $this->error('操作失败！',U('lst'));
                }

            }else{
                $this->error($paper_student->getError());
            }
            return;
        }
        $id=I('id');
        $paper_students=$paper_student->find($id);
        //var_dump($paper_students[topic_state1]);
        //var_dump($paper_students[topic_state2]);
        // if($paper_students[topic_state2]==1)
        //     echo "123";
        //session('aa',$paper_students[topic_state2]);
        var_dump(session('aa'));
         if((session('aa')==1)&&($paper_students[topic_state2]==1)){
                    $aaa=array(
                                'id'    => $id, 
                                'state' => 2, 
                                );
                    $paper_student->create($aaa);
                     $paper_student->save($aaa);
                     
                }else if((session('aa')==1)&&($paper_students[topic_state2]==0)){
                     $bbb=array(
                                'id'    => $id, 
                                'state' => 3, 
                                );
                     $paper_student->create($bbb);
                     $paper_student->save($bbb);
                    
                }else if((session('aa')==0)&&($paper_students[topic_state2]==1)){
                     $ccc=array(
                                'id'    => $id, 
                                'state' => 3, 
                                );
                     $paper_student->create($ccc);
                     $paper_student->save($ccc);
                    
                }else if((session('aa')==0)&&($paper_students[topic_state2]==0)){
                     $ddd=array(
                                'id'    => $id, 
                                'state' => 3, 
                                );
                     $paper_student->create($ddd);
                     $paper_student->save($ddd);
                    
                }
        $this->assign('paper_student',$paper_students);
        $this->display();
    }


}