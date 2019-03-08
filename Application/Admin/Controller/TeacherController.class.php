<?php
namespace Admin\Controller;
use Think\Controller;
class TeacherController extends BaseController{
	public function intoRelation(){
		if(IS_POST){
		$fileInfo=$_FILES['file_stu'];
		//var_dump($fileInfo);die;
		$newName=uploadFile($fileInfo);
		//var_dump($newName);die;

		vendor("PHPExcel.PHPExcel");
        $file_name=$newName;
        //echo($file_name);die;
        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));//判断导入表格后缀格式
        //echo($extension);die;
        if ($extension == 'xlsx') { //判断是什么后缀
        $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        } else if ($extension == 'xls'){
        $objReader =\PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
        }
        $sheet =$objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//取得总行数
        $highestColumn =$sheet->getHighestColumn(); //取得总列数
        //echo($highestColumn);die;
        //D('pro_info')->execute('truncate table pro_info');
        for ($i = 2; $i <= $highestRow; $i++) {
        //看这里看这里,前面小写的a是表中的字段名，后面的大写A是excel中位置
        $data['id'] =trim($objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue());
        $data['member'] =trim($objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue());
        //echo($data);die;
        if ($data['id']=="") {//判断如果表格读到的数据为空 视为结束
            break;
        }
        $data['id']=strval($data['id']);
       //var_dump($data);die;
        $arr[]=$data; //把所有数据存入数组中 前台显示
        $value=D('Teacher')->where('id='.$data['id'])->setField('member',$data['member']);
        if ($value==0) {
            //var_dump($arr[$i-2]);die;
            $arr[$i-2]="Failure of the line import";
        }
        //var_dump($value);die;
       }//for end
       unlink($newName);
       $this->assign('data',$arr);
       $this->show();

	}else{
		$this->show();
	 }
	}
}