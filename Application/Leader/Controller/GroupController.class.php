<?php
namespace Leader\Controller;
use Think\Controller;
class GroupController extends BaseController {
    
    public function opentopic(){
    	if(IS_POST){
    	   //获取组数
    		$zushu = $_POST['zushu'];
    		$name=header('Content-Disposition:attachment;filename="开题答辩分组.xls"');
             $this->randgroup($zushu,$name);
    		}
         $this->display();
    	
    }
    
      public function selecttopic(){
      	 	if(IS_POST){
    	   //获取组数
    		$zushu = $_POST['zushu'];
    		$name=header('Content-Disposition:attachment;filename="选题答辩分组.xls"');
             $this->randgroup($zushu,$name);
    		}
         $this->display();
    }

    //自定义随机分组函数
    public function randgroup($zushu,$name){
    	//教师
    		$teacher = M('Teacher');
        $leader = session('Leader');     
         $data['sid'] = $leader['sid'];
    		$teachers = $teacher->where($data)->select();
    		$tcount = $teacher->where($data)->count();
    		//$tcount = 19;
    		shuffle($teachers);
           //学生
    		$student = M('Student');
    		$students = $student->where($data)->select();
            $scount = $student->where($data)->count();
            //$scount = 100;
            shuffle($students);
           //每组老师数和学生数
           $tshu = intval($tcount/$zushu);
           $sshu = intval($scount/$zushu);
          
          header("content-type:text/html;charset=utf-8");
          //引入phpExcel类
          import("Org.Util.PHPExcel");
          import("Org.Util.PHPExcel.IOFactory");
                 //实例化PHPExcel类
                $PHPReader = new \PHPExcel();
            	$objSheet = $PHPReader->getActiveSheet();
            	$objSheet->setCellValue("A1","组号")->setCellValue("B1","教师号")->setCellValue("C1","学生学号");
             //书写组号
             $k=2;//组号起始位置标记
             $p=$l=$k;//教师号$p开始位置=学号$l开始标记位置=组号$k开始位置
             $q=0;//教师标记数
             $m=0;//学生标记数
            for($i=1;$i<=$zushu;$i++){ 
                    $objSheet->setCellValue("A".$k,"第".$i."组");
                    $k+=$sshu; 
                //书写教师号
               for($t=0;$t<$tshu;$t++){
               	   //$objSheet->setCellValue("B".$p,"第".$q."个");
               	$objSheet->setCellValue("B".$p,$teachers[$q]['id']);
               	   $p++;
               	   $q++;
               }
               //判断最后是否剩人，剩人放最后一组
               if(($i==$zushu)&&($tcount!=$tshu*$zushu)){
               	   for($h=$q;$h<$tcount;$h++){
               	   //$objSheet->setCellValue("B".$p,"第".$q."个");
               	   	$objSheet->setCellValue("B".$p,$teachers[$q]['id']);
               	   	$p++;
               	   	$q++;
               	   }
               }
               $p=$k;//每一组教师号开始位置等于组号位置
                //书写学号
               for($n=0;$n<$sshu;$n++){
               	//$objSheet->setCellValue("C".$l,"第".$m."个");
               	$objSheet->setCellValue("C".$l,$students[$m]['id']);
                $m++;
                $l++;
               }
               //判断最后是否剩人，剩人放最后一组
               if(($i==$zushu)&&($scount%$sshu)!=0){
               	   for($h=$m;$h<$scount;$h++){
               	   	//$objSheet->setCellValue("C".$l,"第".$m."个");
               	   	$objSheet->setCellValue("C".$l,$students[$m]['id']);
               	   $m++;
                   $l++;
               	   }
               }
               $l=$k;
            }
            // for($j=0;$j<$count;$j++){
            // 	$objSheet->setCellValue("A".$i,$students[$j]['id'])->setCellValue("B".$i,$students[$j]['name'])->setCellValue("C".$i,$students[$j]['finally']);
            // 	$i++;
            // }

            $objWriter = \PHPExcel_IOFactory::createWriter($PHPReader,'Excel5');
            header('Content-Type:application/vnd.openxmlformats.officedocument.spreadsheetml.sheet');
            $name;
            header('Cache-Control:max-age=0');
            header('Cache-Controlmax-age=1');
            $objWriter->save('php://output');
    } 

}