<?php
namespace Leader\Controller;
use Think\Controller;
class StudentController extends BaseController {
    //题目审核
    public function auditingtitle(){
    	$student = D('Student');
    	$leader = session('Leader');     
      $data['sid'] = $leader['sid'];
    	$p = empty($_GET['p'])?0:$_GET['p'];
    	$title = $student->selectByDept($p,2,$leader['sid']);
    	$this->assign('students',$title);
    	$count = $student->where($data)->count();
    	$Page = $this->getPages($count,2);

    	$this->assign('fenye',$Page->show());
         $this->display();
    }
    //修改审核状态
    public function revisionreview(){
        $student = D('Student');
        $id = $_GET['id'];
        $status = $_GET['status'];
        $data['id'] = $id;
        $new['topic_state2'] = $status;
            $student->where($data)->save($new);
            $students = $student->where($data)->find();
            if($students['topic_state1']==$$students['topic_state2']&&$$students['topic_state2']==1){
            $new['state'] = 2;
             $student->where($data)->save($new);
            }else{
              $new['state'] = 3;
             $student->where($data)->save($new);
            }
          // if($res){
          $this->redirect('Student/auditingtitle');  
          // }
    }
   public function download(){
            $student = D('Student');
            $p = empty($_GET['p'])?0:$_GET['p'];
            $leader = session('Leader');     
            $data['sid'] = $leader['sid'];
            $students = $student->selectByDept($p,2,$leader['sid']);
            $this->assign('students',$students);
            $count = $student->where($data)->count();
            $Page = $this->getPages($count,2);
            $this->assign('fenye',$Page->show());
            $this->display();  
    }
   //下载学生资料
    public function downloadfile(){
               $student = D('Student');
           // $id = $_GET['id'];
            $path = $_GET['path'];
            //$limit['sid'] = 1;
            //$limit['id']  = $id;
            //$s = $student->where($limit)->find();
            //$data = explode('/', $s['ppt1']);
            //$name = end($data);
            //使用相对路径下载文件
            $url = 'Public/upload/student/'.$path;
            //导入下载类
            import('Org.Net.Http');
            $http = new \Org\Net\Http;
            $http->download($url,$path);
    }
   public function look(){
   	     $student = D('Student');
   	     $p = empty($_GET['p'])?0:$_GET['p'];
         $leader = session('Leader');     
         $data['sid'] = $leader['sid'];
         $students = $student->selectByDept($p,2,$leader['sid']);
         $this->assign('students',$students);
         $count = $student->where($data)->count();
         $Page = $this->getPages($count,2);
         $this->assign('fenye',$Page->show());
         $this->display();
    }
    //输入计算规则
    public function graderule(){
    	$this->display();
    }
   //总成绩计算
    public function gradeadd(){
      $score1rule = $_POST['score1'];
        $score2rule = $_POST['score2'];
        $score3rule = $_POST['score3'];
        $score4rule = $_POST['score4'];
        $student = D('Student');
        $leader = session('Leader');     
         $data['sid'] = $leader['sid'];
        $students = $student->selectByDept($p,2,$leader['sid']);
            $count = $student->where($data)->count();
            for($i=0;$i<$count;$i++){
              $grade=$students[$i]['score1']*$score1rule+$students[$i]['score2']*$score2rule+$students[$i]['score3']*$score3rule+$students[$i]['score4']*$score4rule;
              $data['id'] = $students[$i]['id'];
              $score['finally'] = $grade;
              $res=$student->where($data)->save($score);
              if(!$res){
                $this->error('总成绩更新失败',U('student/look'));
              }
            }
            if($res){
            $this->success('总成绩更新成功',U('student/look'));
          }
          return $this->redirect('student/look');
    }

    public function getfinallygrade(){
    header("content-type:text/html;charset=utf-8");
    //引入phpExcel类
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.IOFactory");
          //文件保存路径
          $filePath = './Uploads/';
           //实例化PHPExcel类
            $PHPReader = new \PHPExcel();
            $student = D('Student');
            $leader = session('Leader');     
         $data['sid'] = $leader['sid'];
    		$students = $student->selectByDept($p,2,$leader['sid']);
            $count = $student->where($data)->count();
            	//$PHPReader->setActiveSheetIndex(1);
            	$objSheet = $PHPReader->getActiveSheet();
            	$objSheet->setCellValue("A1","学号")->setCellValue("B1","姓名")->setCellValue("C1","总成绩");
            $i=2;
            for($j=0;$j<$count;$j++){
            	$objSheet->setCellValue("A".$i,$students[$j]['id'])->setCellValue("B".$i,$students[$j]['name'])->setCellValue("C".$i,$students[$j]['finally']);
            	$i++;
            }

            $objWriter = \PHPExcel_IOFactory::createWriter($PHPReader,'Excel5');
            header('Content-Type:application/vnd.openxmlformats.officedocument.spreadsheetml.sheet');
            header('Content-Disposition:attachment;filename="最终成绩.xls"');
            header('Cache-Control:max-age=0');
            header('Cache-Controlmax-age=1');
            $objWriter->save('php://output');
    }

    public function success(){
    	$this->display();
    }
}